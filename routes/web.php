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
use App\Models\Expense;
use App\Http\Controllers\OfferingController;
use App\Models\OfferingType;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AdministrationFunctionTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AdvancedReportsController;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes de test Firebase (à supprimer en production)
Route::get('/firebase-test', [App\Http\Controllers\FirebaseTestController::class, 'page'])->name('firebase.test');
Route::get('/firebase-test/api', [App\Http\Controllers\FirebaseTestController::class, 'test'])->name('firebase.test.api');

// Route temporaire pour afficher tous les utilisateurs (à supprimer en production)
Route::get('/users-list', function() {
    $users = App\Models\User::with('church', 'role')->get();
    return view('users-list', compact('users'));
})->name('users.list');

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
    $monthStart = now()->copy()->startOfMonth();
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
    // Calculs des totaux pour différentes périodes
    $weekStart = now()->copy()->startOfWeek();
    $yearStart = now()->copy()->startOfYear();
    
    // Totaux pour la période sélectionnée (par défaut: année)
    $totalTithes = Tithe::where('church_id', $churchId)->whereBetween('paid_at', [$from, $to])->sum('amount');
    $totalOfferings = Offering::where('church_id', $churchId)->whereBetween('received_at', [$from, $to])->sum('amount');
    $totalDonations = Donation::where('church_id', $churchId)->whereBetween('received_at', [$from, $to])->sum('amount');
    $totalExpenses = Expense::where('church_id', $churchId)->whereBetween('paid_at', [$from, $to])->sum('amount');
    
    // Totaux pour le mois en cours
    $monthTithes = Tithe::where('church_id', $churchId)->where('paid_at', '>=', $monthStart)->sum('amount');
    $monthOfferings = Offering::where('church_id', $churchId)->where('received_at', '>=', $monthStart)->sum('amount');
    $monthDonations = Donation::where('church_id', $churchId)->where('received_at', '>=', $monthStart)->sum('amount');
    $monthExpenses = Expense::where('church_id', $churchId)->where('paid_at', '>=', $monthStart)->sum('amount');
    
    // Totaux pour la semaine en cours
    $weekTithes = Tithe::where('church_id', $churchId)->where('paid_at', '>=', $weekStart)->sum('amount');
    $weekOfferings = Offering::where('church_id', $churchId)->where('received_at', '>=', $weekStart)->sum('amount');
    $weekDonations = Donation::where('church_id', $churchId)->where('received_at', '>=', $weekStart)->sum('amount');
    $weekExpenses = Expense::where('church_id', $churchId)->where('paid_at', '>=', $weekStart)->sum('amount');
    
    // Totaux pour l'année en cours
    $yearTithes = Tithe::where('church_id', $churchId)->where('paid_at', '>=', $yearStart)->sum('amount');
    $yearOfferings = Offering::where('church_id', $churchId)->where('received_at', '>=', $yearStart)->sum('amount');
    $yearDonations = Donation::where('church_id', $churchId)->where('received_at', '>=', $yearStart)->sum('amount');
    $yearExpenses = Expense::where('church_id', $churchId)->where('paid_at', '>=', $yearStart)->sum('amount');
    
    $stats = [
        'active_members' => $activeMembers,
        'month_total' => $monthTotal,
        'month_donations' => $monthDonations,
        'month_offerings' => $monthOfferings,
        // Totaux par période
        'totals' => [
            'period' => [
                'tithes' => $totalTithes,
                'offerings' => $totalOfferings,
                'donations' => $totalDonations,
                'expenses' => $totalExpenses,
                'income' => $totalTithes + $totalOfferings + $totalDonations,
                'outcome' => $totalExpenses,
                'balance' => ($totalTithes + $totalOfferings + $totalDonations) - $totalExpenses,
            ],
            'month' => [
                'tithes' => $monthTithes,
                'offerings' => $monthOfferings,
                'donations' => $monthDonations,
                'expenses' => $monthExpenses,
                'income' => $monthTithes + $monthOfferings + $monthDonations,
                'outcome' => $monthExpenses,
                'balance' => ($monthTithes + $monthOfferings + $monthDonations) - $monthExpenses,
            ],
            'week' => [
                'tithes' => $weekTithes,
                'offerings' => $weekOfferings,
                'donations' => $weekDonations,
                'expenses' => $weekExpenses,
                'income' => $weekTithes + $weekOfferings + $weekDonations,
                'outcome' => $weekExpenses,
                'balance' => ($weekTithes + $weekOfferings + $weekDonations) - $weekExpenses,
            ],
            'year' => [
                'tithes' => $yearTithes,
                'offerings' => $yearOfferings,
                'donations' => $yearDonations,
                'expenses' => $yearExpenses,
                'income' => $yearTithes + $yearOfferings + $yearDonations,
                'outcome' => $yearExpenses,
                'balance' => ($yearTithes + $yearOfferings + $yearDonations) - $yearExpenses,
            ],
        ],
        // Compatibilité avec l'ancien code
        'total_tithes' => $totalTithes,
        'total_offerings' => $totalOfferings,
        'total_donations' => $totalDonations,
        'total_expenses' => $totalExpenses,
        'total_income' => $totalTithes + $totalOfferings + $totalDonations,
        'total_outcome' => $totalExpenses,
        'net_balance' => ($totalTithes + $totalOfferings + $totalDonations) - $totalExpenses,
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
            'value' => (float) DB::table('expenses')->where('church_id', $churchId)->where('paid_at','>=',$monthStart)->sum('amount'),
            'delta' => $prev['expenses'] == 0 ? null : round(((DB::table('expenses')->where('church_id', $churchId)->where('paid_at','>=',$monthStart)->sum('amount') - $prev['expenses']) / max($prev['expenses'], 0.0001)) * 100, 2),
            'is_expense' => true,
            'current' => (float) DB::table('expenses')->where('church_id', $churchId)->where('paid_at','>=',$monthStart)->sum('amount'),
        ],
    ];
    return view('home', compact('stats','recentTithes','recentDonations','recentOfferings','chart','offeringsByType','from','to','kpis'));
});

Route::resource('members', MemberController::class)->middleware('validate.image.upload');
// Routes pour les remarques des membres
Route::post('members/{member}/remarks', [App\Http\Controllers\MemberRemarkController::class, 'store'])->name('members.remarks.store');
Route::delete('members/{member}/remarks/{index}', [App\Http\Controllers\MemberRemarkController::class, 'destroy'])->name('members.remarks.destroy');
Route::get('members/{member}/remarks', [App\Http\Controllers\MemberRemarkController::class, 'index'])->name('members.remarks.index');

// Routes pour les dîmes (spécifiques avant les routes de ressource)
Route::get('tithes/total', [TitheController::class, 'getTotal'])->name('tithes.total');
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
Route::resource('journal', JournalEntryController::class)->middleware('validate.image.upload');
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

// Routes pour les rapports avancés
Route::prefix('reports/advanced')->name('reports.advanced.')->group(function () {
    Route::get('/', [AdvancedReportsController::class, 'dashboard'])->name('dashboard');
    Route::get('/comparison', [AdvancedReportsController::class, 'comparisonReport'])->name('comparison');
    Route::get('/projection', [AdvancedReportsController::class, 'projectionReport'])->name('projection');
    
    // Exports avancés
    Route::get('/export/excel', [AdvancedReportsController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [AdvancedReportsController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/json', [AdvancedReportsController::class, 'exportJson'])->name('export.json');
    Route::get('/export/csv', [AdvancedReportsController::class, 'exportCsv'])->name('export.csv');
    
    // API pour données temps réel
    Route::get('/api/data', [AdvancedReportsController::class, 'apiData'])->name('api.data');
});

// Routes pour la programmation des cultes




    // Routes pour la gestion des églises
    Route::resource('churches', ChurchController::class);
    
    // Routes pour la gestion des utilisateurs
    Route::resource('user-management', UserManagementController::class);
    Route::post('user-management/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
    
    // Route simple pour afficher l'abonnement
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    
    // Routes de renouvellement d'abonnement
    Route::get('subscription/renewal', [App\Http\Controllers\SubscriptionRenewalController::class, 'renewal'])->name('subscription.renewal');
    Route::post('subscription/process-renewal', [App\Http\Controllers\SubscriptionRenewalController::class, 'processRenewal'])->name('subscription.process-renewal');
    
    // Routes de demande d'abonnement
    Route::get('subscription/request', [App\Http\Controllers\SubscriptionRequestController::class, 'index'])->name('subscription.request');
    Route::post('subscription/request', [App\Http\Controllers\SubscriptionRequestController::class, 'sendRequest'])->name('subscription.request.send');
    
    // Routes d'administration globale (accès direct)
    Route::get('admin-0202', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/churches/{church}', [AdminController::class, 'showChurch'])->name('admin.church-details');
    Route::get('admin/churches/{church}/subscriptions/create', [AdminController::class, 'createSubscription'])->name('admin.create-subscription');
    Route::post('admin/churches/{church}/subscriptions', [AdminController::class, 'storeSubscription'])->name('admin.store-subscription');
    Route::post('admin/churches/{church}/mark-paid', [AdminController::class, 'markSubscriptionPaid'])->name('admin.mark-subscription-paid');
    Route::post('admin/churches/{church}/suspend', [AdminController::class, 'suspendSubscription'])->name('admin.suspend-subscription');
    Route::post('admin/churches/{church}/renew', [AdminController::class, 'renewSubscription'])->name('admin.renew-subscription');
    Route::get('admin/export/churches', [AdminController::class, 'exportChurches'])->name('admin.export-churches');
});
