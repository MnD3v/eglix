<?php

namespace App\Http\Controllers;

use App\Models\Offering;
use App\Models\OfferingType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OfferingController extends Controller
{
    /**
     * Return active offering types as [slug => name] for the current church (or global).
     */
    private function getActiveTypes(): array
    {
        $query = OfferingType::query()
            ->where('is_active', true)
            ->orderBy('name');
        $churchId = Auth::user()->church_id;
        // Prefer church-specific types, plus global (null) types
        $query->where(function ($q) use ($churchId) {
            $q->where('church_id', $churchId)
              ->orWhereNull('church_id');
        });
        return $query->pluck('name', 'slug')->all();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Offering::where('church_id', Auth::user()->church_id);
        $fromInput = $request->query('from');
        $toInput = $request->query('to');
        $fromFilter = $fromInput ? Carbon::parse($fromInput)->startOfDay() : null;
        $toFilter = $toInput ? Carbon::parse($toInput)->endOfDay() : null;
        if ($fromFilter) { $query->where('received_at', '>=', $fromFilter); }
        if ($toFilter) { $query->where('received_at', '<=', $toFilter); }
        $offerings = $query->latest('received_at')->paginate(12)->appends($request->query());

        // Annual chart: prefer current year, fallback to latest year with data
        $year = now()->year;
        if ($fromFilter && $toFilter && $fromFilter->isSameYear($toFilter)) { $year = $fromFilter->year; }
        $from = now()->setYear($year)->copy()->startOfYear();
        $to = now()->setYear($year)->copy()->endOfYear();
        $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
        $driver = DB::getDriverName();
        $monthExpr = match ($driver) {
            'mysql', 'mariadb' => "DATE_FORMAT(received_at, '%Y-%m')",
            'pgsql' => "to_char(received_at, 'YYYY-MM')",
            default => "strftime('%Y-%m', received_at)",
        };
        $rows = DB::table('offerings')
            ->where('church_id', Auth::user()->church_id)
            ->selectRaw("{$monthExpr} as m, sum(amount) as total")
            ->whereBetween('received_at', [$from, $to])
            ->groupBy('m')
            ->pluck('total','m');
        $hasAnyThisYear = collect($rows)->filter(fn($v) => (float)$v > 0)->isNotEmpty();
        if (!$hasAnyThisYear) {
            $latest = DB::table('offerings')->where('church_id', Auth::user()->church_id)->orderByDesc('received_at')->value('received_at');
            if ($latest) {
                $year = (int) substr($latest, 0, 4);
                $from = now()->setYear($year)->copy()->startOfYear();
                $to = now()->setYear($year)->copy()->endOfYear();
                $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
                $rows = DB::table('offerings')
                    ->where('church_id', Auth::user()->church_id)
                    ->selectRaw("{$monthExpr} as m, sum(amount) as total")
                    ->whereBetween('received_at', [$from, $to])
                    ->groupBy('m')
                    ->pluck('total','m');
            }
        }
        $chart = [
            'labels' => $months,
            'data' => $months->map(fn($m) => (float) ($rows[$m] ?? 0))->values(),
            'year' => $year,
        ];

        $filters = [ 'from' => $fromInput, 'to' => $toInput ];

        return view('offerings.index', compact('offerings','chart','filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = $this->getActiveTypes();
        return view('offerings.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $typeSlugs = array_keys($this->getActiveTypes());
        $validated = $request->validate([
            'received_at' => ['required','date'],
            'amount' => ['required','numeric','min:0'],
            'type' => ['required', Rule::in($typeSlugs)],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        // Ajouter l'ID de l'église et l'auteur
        $validated['church_id'] = Auth::user()->church_id;
        $validated['created_by'] = Auth::id();
        
        $o = Offering::create($validated);
        return redirect()->route('offerings.index')->with('success','Offrande enregistrée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offering $offering)
    {
        // Vérifier que l'offrande appartient à l'église de l'utilisateur
        if ($offering->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('offerings.show', compact('offering'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offering $offering)
    {
        // Vérifier que l'offrande appartient à l'église de l'utilisateur
        if ($offering->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $types = $this->getActiveTypes();
        return view('offerings.edit', compact('offering','types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offering $offering)
    {
        // Vérifier que l'offrande appartient à l'église de l'utilisateur
        if ($offering->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $typeSlugs = array_keys($this->getActiveTypes());
        $validated = $request->validate([
            'received_at' => ['required','date'],
            'amount' => ['required','numeric','min:0'],
            'type' => ['required', Rule::in($typeSlugs)],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        $validated['updated_by'] = Auth::id();
        $offering->update($validated);
        return redirect()->route('offerings.index')->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offering $offering)
    {
        // Vérifier que l'offrande appartient à l'église de l'utilisateur
        if ($offering->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $offering->delete();
        return redirect()->route('offerings.index')->with('success','Offrande supprimée.');
    }

    public function bulk()
    {
        $types = $this->getActiveTypes(); // [slug => name]
        return view('offerings.bulk', compact('types'));
    }

    public function bulkStore(Request $request)
    {
        $types = array_keys($this->getActiveTypes());
        $rules = [
            'received_at' => ['required','date'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ];
        foreach ($types as $t) {
            $rules["amount_$t"] = ['nullable','numeric','min:0'];
        }
        $validated = $request->validate($rules);

        $created = 0;
        foreach ($types as $t) {
            $amount = (float)($validated["amount_$t"] ?? 0);
            if ($amount > 0) {
                Offering::create([
                    'received_at' => $validated['received_at'],
                    'amount' => $amount,
                    'type' => $t,
                    'payment_method' => $validated['payment_method'] ?? null,
                    'reference' => $validated['reference'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
                $created++;
            }
        }

        return redirect()->route('offerings.index')->with('success', $created.' offrandes enregistrées.');
    }
}
