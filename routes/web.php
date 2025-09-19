<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TitheController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Tithe;
use App\Models\Donation;
use App\Models\Offering;
use App\Http\Controllers\OfferingController;
use App\Models\OfferingType;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ChurchEventController;
use App\Http\Controllers\ServiceRoleController;
use App\Http\Controllers\ServiceProgramController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AdministrationFunctionTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function (Request $request) {
    $churchId = Auth::user()->church_id;
    $activeMembers = Member::where('church_id', $churchId)->where('status', 'active')->count();
    // Default to the full current year unless explicit dates are provided
    $from = $request->filled('from')
        ? \Carbon\Carbon::parse($request->get('from'))->startOfDay()
        : now()->copy()->startOfYear();
    $to = $request->filled('to')
        ? \Carbon\Carbon::parse($request->get('to'))->endOfDay()
        : now()->copy()->endOfYear();
    $monthStart = $to->copy()->startOfMonth();
    $prevStart = (clone $monthStart)->subMonth();
    $prevEnd = (clone $monthStart)->subDay()->endOfDay();
    $monthTotal = Tithe::where('church_id', $churchId)->where('paid_at', '>=', $monthStart)->sum('amount');
    $recentTithes = Tithe::with('member')->where('church_id', $churchId)->latest('paid_at')->limit(5)->get();
    $monthDonations = Donation::where('church_id', $churchId)->where('received_at', '>=', $monthStart)->sum('amount');
    $recentDonations = Donation::with(['member','project'])->where('church_id', $churchId)->latest('received_at')->limit(5)->get();
    $monthOfferings = Offering::where('church_id', $churchId)->where('received_at', '>=', $monthStart)->sum('amount');
    $recentOfferings = Offering::with('member')->where('church_id', $churchId)->orderBy('received_at', 'desc')->limit(5)->get();
    // previous month totals
    $prev = [
        'tithes' => (float) DB::table('tithes')->where('church_id', $churchId)->whereBetween('paid_at', [$prevStart, $prevEnd])->sum('amount'),
        'offerings' => (float) DB::table('offerings')->where('church_id', $churchId)->whereBetween('received_at', [$prevStart, $prevEnd])->sum('amount'),
        'donations' => (float) DB::table('donations')->where('church_id', $churchId)->whereBetween('received_at', [$prevStart, $prevEnd])->sum('amount'),
        'expenses' => (float) DB::table('expenses')->where('church_id', $churchId)->whereBetween('paid_at', [$prevStart, $prevEnd])->sum('amount'),
    ];
    // Aggregates for charts (full year by default)
    // Build list of months between $from and $to (cap to 24 months for safety)
    $months = collect();
    $cursor = $from->copy()->startOfMonth();
    $limit = 0;
    while ($cursor <= $to->copy()->startOfMonth() && $limit < 24) {
        $months->push($cursor->format('Y-m'));
        $cursor->addMonth();
        $limit++;
    }
    if ($months->isEmpty()) { $months = collect([$to->copy()->startOfMonth()->format('Y-m')]); }

    $totalsByMonth = function ($table, $dateCol) use ($months, $from, $to, $churchId) {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            $monthExpr = "to_char(date_trunc('month', $dateCol), 'YYYY-MM')";
        } elseif ($driver === 'mysql') {
            $monthExpr = "DATE_FORMAT($dateCol, '%Y-%m')";
        } else { // sqlite and others
            $monthExpr = "strftime('%Y-%m', $dateCol)";
        }

        $rows = DB::table($table)
            ->where('church_id', $churchId)
            ->selectRaw("$monthExpr as m, SUM(amount) as total")
            ->whereBetween($dateCol, [$from, $to])
            ->groupBy('m')
            ->pluck('total', 'm');

        return $months->map(fn ($m) => (float) ($rows[$m] ?? 0));
    };
    $chart = [
        'labels' => $months,
        'tithes' => $totalsByMonth('tithes','paid_at'),
        'offerings' => $totalsByMonth('offerings','received_at'),
        'donations' => $totalsByMonth('donations','received_at'),
        'expenses' => $totalsByMonth('expenses','paid_at'),
    ];
    // Offerings by type (current month) - use actual types from offerings
    $offeringsByType = Offering::where('church_id', $churchId)->whereBetween('received_at', [$monthStart, $to])
        ->whereNotNull('type')
        ->where('type', '!=', '')
        ->selectRaw('type, SUM(amount) as total')
        ->groupBy('type')
        ->pluck('total', 'type')
        ->map(fn($amount) => (float)$amount);
    $stats = [
        'active_members' => $activeMembers,
        'month_total' => $monthTotal,
        'month_donations' => $monthDonations,
        'month_offerings' => $monthOfferings,
    ];
    $kpis = [
        [
            'label' => 'Dîmes',
            'value' => (float) $monthTotal,
            'delta' => $prev['tithes'] == 0 ? null : round((($monthTotal - $prev['tithes']) / max($prev['tithes'], 0.0001)) * 100, 2),
        ],
        [
            'label' => 'Offrandes',
            'value' => (float) $monthOfferings,
            'delta' => $prev['offerings'] == 0 ? null : round((($monthOfferings - $prev['offerings']) / max($prev['offerings'], 0.0001)) * 100, 2),
        ],
        [
            'label' => 'Dons',
            'value' => (float) $monthDonations,
            'delta' => $prev['donations'] == 0 ? null : round((($monthDonations - $prev['donations']) / max($prev['donations'], 0.0001)) * 100, 2),
        ],
        [
            'label' => 'Dépenses',
            'value' => (float) ($year['expenses'] ?? 0) + 0 - 0, // placeholder, not used
            'delta' => $prev['expenses'] == 0 ? null : round(((DB::table('expenses')->where('church_id', $churchId)->where('paid_at','>=',$monthStart)->sum('amount') - $prev['expenses']) / max($prev['expenses'], 0.0001)) * 100, 2),
            'is_expense' => true,
            'current' => (float) DB::table('expenses')->where('church_id', $churchId)->where('paid_at','>=',$monthStart)->sum('amount'),
        ],
    ];
    return view('home', compact('stats','recentTithes','recentDonations','recentOfferings','chart','offeringsByType','from','to','kpis'));
});

Route::resource('members', MemberController::class);
// Routes pour les remarques des membres
Route::post('members/{member}/remarks', [App\Http\Controllers\MemberRemarkController::class, 'store'])->name('members.remarks.store');
Route::delete('members/{member}/remarks/{index}', [App\Http\Controllers\MemberRemarkController::class, 'destroy'])->name('members.remarks.destroy');
Route::get('members/{member}/remarks', [App\Http\Controllers\MemberRemarkController::class, 'index'])->name('members.remarks.index');
Route::resource('tithes', TitheController::class);
Route::resource('offerings', OfferingController::class);
// Types d'offrandes
Route::resource('offering-types', App\Http\Controllers\OfferingTypeController::class);
Route::post('offering-types/{offering_type}/toggle', [App\Http\Controllers\OfferingTypeController::class, 'toggle'])->name('offering-types.toggle');
Route::get('offerings-bulk', [OfferingController::class, 'bulk'])->name('offerings.bulk');
Route::post('offerings-bulk', [OfferingController::class, 'bulkStore'])->name('offerings.bulk.store');
Route::resource('donations', DonationController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('projects', ProjectController::class);
Route::resource('services', ServiceController::class);
Route::resource('service-roles', ServiceRoleController::class);
Route::post('service-roles/reset-defaults', [ServiceRoleController::class, 'resetDefaults'])->name('service-roles.reset-defaults');
Route::resource('events', ChurchEventController::class);
Route::resource('journal', JournalEntryController::class);
Route::resource('administration', AdministrationController::class);
Route::resource('administration-function-types', AdministrationFunctionTypeController::class);
Route::post('administration-function-types/{administrationFunctionType}/toggle', [AdministrationFunctionTypeController::class, 'toggle'])->name('administration-function-types.toggle');

// Routes pour les rapports
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
Route::get('/reports/tithes', [ReportsController::class, 'tithes'])->name('reports.tithes');
Route::get('/reports/offerings', [ReportsController::class, 'offerings'])->name('reports.offerings');
Route::get('/reports/donations', [ReportsController::class, 'donations'])->name('reports.donations');
Route::get('/reports/expenses', [ReportsController::class, 'expenses'])->name('reports.expenses');

// Routes pour les exports
Route::get('/reports/tithes/export', [ReportsController::class, 'exportTithes'])->name('reports.tithes.export');
Route::get('/reports/offerings/export', [ReportsController::class, 'exportOfferings'])->name('reports.offerings.export');
Route::get('/reports/donations/export', [ReportsController::class, 'exportDonations'])->name('reports.donations.export');
Route::get('/reports/expenses/export', [ReportsController::class, 'exportExpenses'])->name('reports.expenses.export');

// Routes pour la programmation des cultes
Route::get('services/{service}/program', [ServiceProgramController::class, 'show'])->name('services.program');
Route::post('services/{service}/assign', [ServiceProgramController::class, 'assign'])->name('services.assign');
Route::delete('service-assignments/{assignment}', [ServiceProgramController::class, 'unassign'])->name('service-assignments.destroy');



Route::get('agenda', function () {
    $from = now()->startOfMonth();
    $to = now()->copy()->addMonths(2)->endOfMonth();
    $services = \App\Models\Service::whereBetween('date', [$from,$to])->orderBy('date')->get();
    $events = \App\Models\ChurchEvent::whereBetween('date', [$from,$to])->orderBy('date')->get();
    return view('agenda.index', compact('services','events','from','to'));
})->name('agenda.index');

    // Routes pour la gestion des églises
    Route::resource('churches', ChurchController::class);
    
    // Routes pour la gestion des utilisateurs
    Route::resource('user-management', UserManagementController::class);
    Route::post('user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
});
