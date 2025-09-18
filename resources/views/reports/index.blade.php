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

.btn-primary.action-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
}

.btn-primary.action-btn:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    color: white;
}

.btn-success.action-btn {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border-color: transparent;
    color: white;
}

.btn-success.action-btn:hover {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
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

.btn-primary.btn-sm {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
}

.btn-primary.btn-sm:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-success.btn-sm {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    border-color: transparent;
    color: white;
}

.btn-success.btn-sm:hover {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Rapports Financiers</h1>
        <p class="page-subtitle">Analysez les données financières de votre église</p>
        <div class="d-flex align-items-center gap-2 mt-2">
            <i class="bi bi-calendar3 text-muted"></i>
            <span class="text-muted">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</span>
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
                    <button type="submit" class="btn btn-primary w-100">
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
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #6366f1;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5 class="report-title">Rapport Dîmes</h5>
                    <p class="report-description">Analyse des dîmes collectées</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="{{ route('reports.tithes', ['from' => $from, 'to' => $to]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #f97316;">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <h5 class="report-title">Rapport Offrandes</h5>
                    <p class="report-description">Analyse des offrandes reçues</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="{{ route('reports.offerings', ['from' => $from, 'to' => $to]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('reports.offerings.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); color: #8b5cf6;">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <h5 class="report-title">Rapport Dons</h5>
                    <p class="report-description">Analyse des dons reçus</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="{{ route('reports.donations', ['from' => $from, 'to' => $to]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('reports.donations.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i> CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="report-icon mx-auto" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%); color: #0ea5e9;">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <h5 class="report-title">Rapport Dépenses</h5>
                    <p class="report-description">Analyse des dépenses effectuées</p>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a href="{{ route('reports.expenses', ['from' => $from, 'to' => $to]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('reports.expenses.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i> CSV
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