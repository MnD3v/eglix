<?php

namespace App\Http\Controllers;

use App\Models\Tithe;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TitheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q'));
        $query = Tithe::with('member')->where('church_id', Auth::user()->church_id);

        // Date range filters
        $fromInput = $request->query('from');
        $toInput = $request->query('to');
        $fromFilter = $fromInput ? Carbon::parse($fromInput)->startOfDay() : null;
        $toFilter = $toInput ? Carbon::parse($toInput)->endOfDay() : null;

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('payment_method', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($fromFilter) { $query->where('paid_at', '>=', $fromFilter); }
        if ($toFilter) { $query->where('paid_at', '<=', $toFilter); }

        $tithes = $query->latest('paid_at')->paginate(12)->withQueryString();

        // Chart data
        $driver = DB::getDriverName();
        if (!$fromFilter && !$toFilter) {
            // AUCUN FILTRE: agréger toutes les dîmes par mois (1..12) sur toute l'historique
            $monthExpr = match ($driver) {
                'mysql', 'mariadb' => "DATE_FORMAT(paid_at, '%m')",
                'pgsql' => "to_char(paid_at, 'MM')",
                default => "strftime('%m', paid_at)",
            };
            $rows = DB::table('tithes')
                ->where('church_id', Auth::user()->church_id)
                ->selectRaw("{$monthExpr} as m, SUM(amount) as total")
                ->groupBy('m')
                ->pluck('total', 'm');
            $labelsNumeric = collect(range(1,12));
            $chart = [
                'labels' => $labelsNumeric->map(fn($n) => sprintf('%02d', $n)),
                'data' => $labelsNumeric->map(fn($n) => (float) ($rows[sprintf('%02d',$n)] ?? 0))->values(),
                'year' => 'Tout',
                'labels_numeric' => $labelsNumeric,
            ];
        } else {
            // FILTRE PRÉSENT: préférer l'année sélectionnée, sinon année courante; fallback à dernière année avec données
            $year = now()->year;
            if ($fromFilter && $toFilter && $fromFilter->isSameYear($toFilter)) {
                $year = $fromFilter->year;
            }
            $from = now()->setYear($year)->copy()->startOfYear();
            $to = now()->setYear($year)->copy()->endOfYear();
            $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
            $monthExpr = match ($driver) {
                'mysql', 'mariadb' => "DATE_FORMAT(paid_at, '%Y-%m')",
                'pgsql' => "to_char(paid_at, 'YYYY-MM')",
                default => "strftime('%Y-%m', paid_at)", // sqlite & others
            };
            $rows = DB::table('tithes')
                ->where('church_id', Auth::user()->church_id)
                ->selectRaw("{$monthExpr} as m, sum(amount) as total")
                ->whereBetween('paid_at', [$from, $to])
                ->groupBy('m')
                ->pluck('total','m');
            $hasAnyThisYear = collect($rows)->filter(fn($v) => (float)$v > 0)->isNotEmpty();
            if (!$hasAnyThisYear) {
                $latest = DB::table('tithes')->where('church_id', Auth::user()->church_id)->orderByDesc('paid_at')->value('paid_at');
                if ($latest) {
                    $year = (int) substr($latest, 0, 4);
                    $from = now()->setYear($year)->copy()->startOfYear();
                    $to = now()->setYear($year)->copy()->endOfYear();
                    $months = collect(range(1,12))->map(fn($m) => sprintf('%04d-%02d', $year, $m));
                    $rows = DB::table('tithes')
                        ->where('church_id', Auth::user()->church_id)
                        ->selectRaw("{$monthExpr} as m, sum(amount) as total")
                        ->whereBetween('paid_at', [$from, $to])
                        ->groupBy('m')
                        ->pluck('total','m');
                }
            }
            $chart = [
                'labels' => $months,
                'data' => $months->map(fn($m) => (float) ($rows[$m] ?? 0))->values(),
                'year' => $year,
                'labels_numeric' => $months->map(fn($m) => (int) \Carbon\Carbon::createFromFormat('Y-m', $m)->format('n'))->values(),
            ];
        }

        $filters = [ 'from' => $fromInput, 'to' => $toInput ];
        return view('tithes.index', compact('tithes', 'search', 'chart', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::where('church_id', Auth::user()->church_id)
            ->orderBy('last_name')->orderBy('first_name')->get();
        return view('tithes.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required','exists:members,id'],
            'paid_at' => ['required','date'],
            'amount' => ['required','numeric','min:0'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);

        // Ajouter l'ID de l'église
        $validated['church_id'] = Auth::user()->church_id;
        $validated['created_by'] = Auth::id();

        $tithe = Tithe::create($validated);
        $redirect = $request->string('redirect')->toString();
        if (!empty($redirect)) {
            return redirect($redirect)->with('success', 'Dîme enregistrée.');
        }
        return redirect()->route('tithes.show', $tithe)->with('success', 'Dîme enregistrée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tithe $tithe)
    {
        // Vérifier que la dîme appartient à l'église de l'utilisateur
        if ($tithe->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $tithe->load('member');
        return view('tithes.show', compact('tithe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tithe $tithe)
    {
        // Vérifier que la dîme appartient à l'église de l'utilisateur
        if ($tithe->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $members = Member::where('church_id', Auth::user()->church_id)
            ->orderBy('last_name')->orderBy('first_name')->get();
        return view('tithes.edit', compact('tithe','members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tithe $tithe)
    {
        // Vérifier que la dîme appartient à l'église de l'utilisateur
        if ($tithe->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'member_id' => ['required','exists:members,id'],
            'paid_at' => ['required','date'],
            'amount' => ['required','numeric','min:0'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        $validated['updated_by'] = Auth::id();
        $tithe->update($validated);
        return redirect()->route('tithes.show', $tithe)->with('success', 'Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tithe $tithe)
    {
        // Vérifier que la dîme appartient à l'église de l'utilisateur
        if ($tithe->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $tithe->delete();
        return redirect()->route('tithes.index')->with('success', 'Dîme supprimée.');
    }
}
