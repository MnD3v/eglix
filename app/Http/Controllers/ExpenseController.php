<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with('project')->where('church_id', Auth::user()->church_id);

        $fromInput = $request->query('from');
        $toInput = $request->query('to');
        $fromFilter = $fromInput ? Carbon::parse($fromInput)->startOfDay() : null;
        $toFilter = $toInput ? Carbon::parse($toInput)->endOfDay() : null;

        if ($fromFilter) {
            $query->where('paid_at', '>=', $fromFilter);
        }
        if ($toFilter) {
            $query->where('paid_at', '<=', $toFilter);
        }

        $expenses = $query->latest('paid_at')->paginate(12)->appends($request->query());

        // Chart data: current year by default, or filtered year if within same year; fallback to latest year with data
        $year = now()->year;
        if ($fromFilter && $toFilter && $fromFilter->isSameYear($toFilter)) {
            $year = $fromFilter->year;
        }
        $yearStart = Carbon::create($year, 1, 1, 0, 0, 0);
        $yearEnd = Carbon::create($year, 12, 31, 23, 59, 59);

        $rangeStart = $fromFilter && $fromFilter->year === $year ? $fromFilter : $yearStart;
        $rangeEnd = $toFilter && $toFilter->year === $year ? $toFilter : $yearEnd;

        $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
        $driver = DB::getDriverName();
        $monthExpr = match ($driver) {
            'mysql', 'mariadb' => "DATE_FORMAT(paid_at, '%Y-%m')",
            'pgsql' => "to_char(paid_at, 'YYYY-MM')",
            default => "strftime('%Y-%m', paid_at)",
        };
        $rows = DB::table('expenses')
            ->where('church_id', Auth::user()->church_id)
            ->selectRaw("{$monthExpr} as m, sum(amount) as total")
            ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
            ->groupBy('m')
            ->pluck('total','m');
        $hasAnyForYear = collect($rows)->filter(fn($v) => (float)$v > 0)->isNotEmpty();
        if (!$hasAnyForYear) {
            $latest = DB::table('expenses')->where('church_id', Auth::user()->church_id)->orderByDesc('paid_at')->value('paid_at');
            if ($latest) {
                $year = (int) substr($latest, 0, 4);
                $yearStart = Carbon::create($year, 1, 1, 0, 0, 0);
                $yearEnd = Carbon::create($year, 12, 31, 23, 59, 59);
                $rangeStart = $yearStart;
                $rangeEnd = $yearEnd;
                $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
                $rows = DB::table('expenses')
                    ->where('church_id', Auth::user()->church_id)
                    ->selectRaw("{$monthExpr} as m, sum(amount) as total")
                    ->whereBetween('paid_at', [$rangeStart, $rangeEnd])
                    ->groupBy('m')
                    ->pluck('total','m');
            }
        }
        $chart = [
            'labels' => $months,
            'data' => $months->map(fn($m) => (float) ($rows[$m] ?? 0))->values(),
            'year' => $year,
        ];

        $filters = [
            'from' => $fromInput,
            'to' => $toInput,
        ];

        return view('expenses.index', compact('expenses','chart','filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();
        return view('expenses.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['nullable','exists:projects,id'],
            'paid_at' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'title' => ['nullable','string','max:150'],
            'amount' => ['required','numeric','min:0'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        // If toggle is off, ensure project_id is null
        if (!$request->boolean('has_project')) {
            $validated['project_id'] = null;
        }
        // If not linked to a project, map title to description if provided
        if (!$request->boolean('has_project') && !empty($validated['title'])) {
            $validated['description'] = $validated['title'];
        }
        unset($validated['title']);
        // Ajouter l'ID de l'église et l'auteur
        $validated['church_id'] = Auth::user()->church_id;
        $validated['created_by'] = Auth::id();
        
        $e = Expense::create($validated);
        return redirect()->route('expenses.index')->with('success','Dépense enregistrée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        // Vérifier que la dépense appartient à l'église de l'utilisateur
        if ($expense->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $expense->load('project');
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        // Vérifier que la dépense appartient à l'église de l'utilisateur
        if ($expense->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $projects = Project::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();
        return view('expenses.edit', compact('expense','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Vérifier que la dépense appartient à l'église de l'utilisateur
        if ($expense->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'project_id' => ['nullable','exists:projects,id'],
            'paid_at' => ['required','date'],
            'description' => ['nullable','string','max:255'],
            'title' => ['nullable','string','max:150'],
            'amount' => ['required','numeric','min:0'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        if (!$request->boolean('has_project')) {
            $validated['project_id'] = null;
        }
        if (!$request->boolean('has_project') && !empty($validated['title'])) {
            $validated['description'] = $validated['title'];
        }
        unset($validated['title']);
        $validated['updated_by'] = Auth::id();
        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        // Vérifier que la dépense appartient à l'église de l'utilisateur
        if ($expense->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $expense->delete();
        return redirect()->route('expenses.index')->with('success','Dépense supprimée.');
    }
}
