@extends('layouts.app')

@section('content')
<style>
.report-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    overflow: hidden;
    position: relative;
    padding: 1.5rem;
}

.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-color: #e8f0fe;
}

.report-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.report-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 16px;
}

.report-title {
    color: #202124;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.report-description {
    color: #5f6368;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.report-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: 1px solid;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn.action-btn {
    background: #4a6cf7;
    border-color: transparent;
    color: white;
}

.btn.action-btn:hover {
    background: #3d5bd9;
    color: white;
}

.btn-success.action-btn {
    background: #34c759;
    border-color: transparent;
    color: white;
}

.btn-success.action-btn:hover {
    background: #28a745;
    color: white;
}
}

.report-title {
    color: #202124;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.report-description {
    color: #5f6368;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.period-filter {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e8eaed;
}

.filter-title {
    color: #202124;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.quick-filter-btn {
    background: #ffffff;
    border: 1px solid #dadce0;
    color: #5f6368;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.quick-filter-btn:hover {
    background: #f8f9fa;
    border-color: #5f6368;
    color: #202124;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.quick-filter-btn.active {
    background: #1a73e8;
    border-color: #1a73e8;
    color: white;
}

.quick-filter-btn.active:hover {
    background: #1557b0;
    border-color: #1557b0;
    color: white;
}

.btn-sm {
    border-radius: 12px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    border: 1px solid;
}

.btn.btn-sm {
    background: #4a6cf7;
    border-color: transparent;
    color: white;
}

.btn.btn-sm:hover {
    background: #3d5bd9;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(74, 108, 247, 0.3);
}

.btn-success.btn-sm {
    background: #34c759;
    border-color: transparent;
    color: white;
}

.btn-success.btn-sm:hover {
    background: #28a745;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(52, 199, 89, 0.3);
}

.btn-danger.btn-sm {
    background: #ff3b30;
    border-color: transparent;
    color: white;
}

.btn-danger.btn-sm:hover {
    background: #dc3545;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(255, 59, 48, 0.3);
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-graph-up me-3"></i>
                    Rapports Financiers
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-bar-chart me-2"></i>
                    Analysez les données financières de votre église
                </p>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-calendar3 text-muted"></i>
                    <span class="text-muted">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre de période -->
    <div class="period-filter">
        <h5 class="filter-title">Période d'analyse</h5>
        
        <form method="GET" id="periodForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de début</label>
                    <input type="date" class="form-control" name="from" value="{{ $from }}" id="fromDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de fin</label>
                    <input type="date" class="form-control" name="to" value="{{ $to }}" id="toDate">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn w-100">
                        <i class="bi bi-funnel"></i> Appliquer
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="resetPeriod()">
                        <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </form>

        <!-- Filtres rapides -->
        <div class="mt-3">
            <p class="small text-muted mb-2">Périodes rapides :</p>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('year')">
                <i class="bi bi-calendar3"></i> Année en cours
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('lastYear')">
                <i class="bi bi-calendar2"></i> Année dernière
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('last6months')">
                <i class="bi bi-calendar-month"></i> 6 derniers mois
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('last3months')">
                <i class="bi bi-calendar-week"></i> 3 derniers mois
            </a>
        </div>
    </div>

    <!-- Cartes des rapports -->
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100" onclick="window.location='{{ route('reports.tithes', ['from' => $from, 'to' => $to]) }}'">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: #f0f7ff; color: #4a6cf7;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5 class="report-title">Rapport Dîmes</h5>
                    <p class="report-description">Analyse des dîmes collectées</p>
                    <div class="d-flex gap-2 justify-content-center mt-3" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100" onclick="window.location='{{ route('reports.offerings', ['from' => $from, 'to' => $to]) }}'">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: #fff0e6; color: #ff6b35;">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h5 class="report-title">Rapport Offrandes</h5>
                    <p class="report-description">Analyse des offrandes reçues</p>
                    <div class="d-flex gap-2 justify-content-center mt-3" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.offerings.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reports.offerings.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100" onclick="window.location='{{ route('reports.donations', ['from' => $from, 'to' => $to]) }}'">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: #f5f0ff; color: #7b61ff;">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <h5 class="report-title">Rapport Dons</h5>
                    <p class="report-description">Analyse des dons reçus</p>
                    <div class="d-flex gap-2 justify-content-center mt-3" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.donations.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reports.donations.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100" onclick="window.location='{{ route('reports.expenses', ['from' => $from, 'to' => $to]) }}'">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: #e6f7ff; color: #0099e5;">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <h5 class="report-title">Rapport Dépenses</h5>
                    <p class="report-description">Analyse des dépenses effectuées</p>
                    <div class="d-flex gap-2 justify-content-center mt-3" onclick="event.stopPropagation()">
                        <a href="{{ route('reports.expenses.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reports.expenses.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setQuickPeriod(period) {
    const today = new Date();
    let from, to;
    
    switch(period) {
        case 'year':
            from = new Date(today.getFullYear(), 0, 1);
            to = new Date(today.getFullYear(), 11, 31);
            break;
        case 'lastYear':
            from = new Date(today.getFullYear() - 1, 0, 1);
            to = new Date(today.getFullYear() - 1, 11, 31);
            break;
        case 'last6months':
            from = new Date(today.getFullYear(), today.getMonth() - 6, 1);
            to = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        case 'last3months':
            from = new Date(today.getFullYear(), today.getMonth() - 3, 1);
            to = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
    }
    
    document.getElementById('fromDate').value = from.toISOString().split('T')[0];
    document.getElementById('toDate').value = to.toISOString().split('T')[0];
    
    // Mettre à jour les liens des cartes
    updateReportLinks();
}

function resetPeriod() {
    const currentYear = new Date().getFullYear();
    document.getElementById('fromDate').value = currentYear + '-01-01';
    document.getElementById('toDate').value = currentYear + '-12-31';
    updateReportLinks();
}

function updateReportLinks() {
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    
    // Mettre à jour tous les liens des rapports
    document.querySelectorAll('a[href*="reports.tithes"]').forEach(link => {
        link.href = `{{ route('reports.tithes') }}?from=${from}&to=${to}`;
    });
    
    document.querySelectorAll('a[href*="reports.offerings"]').forEach(link => {
        link.href = `{{ route('reports.offerings') }}?from=${from}&to=${to}`;
    });
    
    document.querySelectorAll('a[href*="reports.donations"]').forEach(link => {
        link.href = `{{ route('reports.donations') }}?from=${from}&to=${to}`;
    });
    
    document.querySelectorAll('a[href*="reports.expenses"]').forEach(link => {
        link.href = `{{ route('reports.expenses') }}?from=${from}&to=${to}`;
    });
}

// Mettre à jour les liens quand les dates changent
document.getElementById('fromDate').addEventListener('change', updateReportLinks);
document.getElementById('toDate').addEventListener('change', updateReportLinks);
</script>
@endsection