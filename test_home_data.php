<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Auth;
use App\Models\User;
use App\Models\Tithe;
use App\Models\Donation;
use App\Models\Offering;
use App\Models\Expense;
use Carbon\Carbon;

// Simuler exactement la logique de routes/web.php
$user = User::first();
Auth::login($user);

$churchId = Auth::user()->church_id;
$monthStart = Carbon::now()->copy()->startOfMonth();

// Variables exactes de la route
$monthTotal = Tithe::where('church_id', $churchId)->where('paid_at', '>=', Carbon::now()->startOfYear())->sum('amount');
$monthDonations = Donation::where('church_id', $churchId)->where('received_at', '>=', Carbon::now()->startOfYear())->sum('amount');
$monthOfferings = Offering::where('church_id', $churchId)->where('received_at', '>=', Carbon::now()->startOfYear())->sum('amount');

echo "=== SIMULATION DE LA ROUTE HOME ===\n";
echo "Church ID: {$churchId}\n";
echo "Month Start: {$monthStart}\n";
echo "Month Total (Tithes): {$monthTotal}\n";
echo "Month Donations: {$monthDonations}\n";
echo "Month Offerings: {$monthOfferings}\n";

// Test des KPIs exactement comme dans routes/web.php
$kpis = [
    [
        'label' => 'Dîmes',
        'value' => (float) $monthTotal,
    ],
    [
        'label' => 'Offrandes',
        'value' => (float) $monthOfferings,
    ],
    [
        'label' => 'Dons',
        'value' => (float) $monthDonations,
    ],
];

echo "\n=== KPIS ===\n";
foreach ($kpis as $kpi) {
    echo "{$kpi['label']}: {$kpi['value']}\n";
}
