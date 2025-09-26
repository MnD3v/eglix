@extends('layouts.app')

@section('content')
<style>
/* Styles pour la liste des rapports */
.reports-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.report-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.report-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.report-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.report-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.report-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.report-title {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.report-description {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.report-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.report-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.report-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
.date-input {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.date-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

.quick-filter-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    border-radius: 12px;
    padding: 8px 16px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 8px;
    margin-bottom: 8px;
}

.quick-filter-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #475569;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    border-radius: 8px;
    padding: 6px 12px;
    font-weight: 500;
    font-size: 12px;
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

/* Icônes noires dans toute la section rapports */
.reports-list .bi,
.reports-appbar .bi,
.report-description .bi,
.report-row-empty .bi,
.filter-btn .bi,
.quick-filter-btn .bi {
    color: #000000 !important;
}

.quick-filter-btn.active .bi {
    color: white !important;
}
</style>

<div class="container py-4">
    <!-- AppBar Rapports -->
    <div class="appbar reports-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Rapports Financiers</h1>
                </div>
            </div>
            <div class="appbar-right">
                <div class="period-display me-2">
                    <div class="period-text">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</div>
                    <small class="period-label">Période d'analyse</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre de période -->
    <div class="card card-soft p-3 mb-3">
        <h5 class="mb-3">Période d'analyse</h5>
        
        <form method="GET" id="periodForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de début</label>
                    <input type="date" class="form-control date-input" name="from" value="{{ $from }}" id="fromDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de fin</label>
                    <input type="date" class="form-control date-input" name="to" value="{{ $to }}" id="toDate">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn filter-btn w-100">
                        <i class="bi bi-funnel"></i> Appliquer
                    </button>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </form>

        <!-- Filtres rapides -->
        <div class="mt-3">
            <p class="small text-muted mb-2">Périodes rapides :</p>
            <a href="{{ route('reports.index', ['from' => \Carbon\Carbon::now()->startOfYear()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->endOfYear()->format('Y-m-d')]) }}" class="quick-filter-btn">
                <i class="bi bi-calendar3"></i> Année en cours
            </a>
            <a href="{{ route('reports.index', ['from' => \Carbon\Carbon::now()->subYear()->startOfYear()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->subYear()->endOfYear()->format('Y-m-d')]) }}" class="quick-filter-btn">
                <i class="bi bi-calendar2"></i> Année dernière
            </a>
            <a href="{{ route('reports.index', ['from' => \Carbon\Carbon::now()->subMonths(6)->startOfMonth()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')]) }}" class="quick-filter-btn">
                <i class="bi bi-calendar-month"></i> 6 derniers mois
            </a>
            <a href="{{ route('reports.index', ['from' => \Carbon\Carbon::now()->subMonths(3)->startOfMonth()->format('Y-m-d'), 'to' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')]) }}" class="quick-filter-btn">
                <i class="bi bi-calendar-week"></i> 3 derniers mois
            </a>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="reports-list">
        <div class="report-row">
            <div class="report-row-body">
                <div class="report-info">
                    <div class="report-title">
                        <a href="{{ route('reports.tithes', ['from' => $from, 'to' => $to]) }}" class="link-dark text-decoration-none">Rapport Dîmes</a>
                    </div>
                    <div class="report-description">
                        <i class="bi bi-wallet2 me-1"></i>Analyse des dîmes collectées
                    </div>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="report-row report-row-separated">
            <div class="report-row-body">
                <div class="report-info">
                    <div class="report-title">
                        <a href="{{ route('reports.offerings', ['from' => $from, 'to' => $to]) }}" class="link-dark text-decoration-none">Rapport Offrandes</a>
                    </div>
                    <div class="report-description">
                        <i class="bi bi-heart-fill me-1"></i>Analyse des offrandes reçues
                    </div>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.offerings.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    <a href="{{ route('reports.offerings.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="report-row report-row-separated">
            <div class="report-row-body">
                <div class="report-info">
                    <div class="report-title">
                        <a href="{{ route('reports.donations', ['from' => $from, 'to' => $to]) }}" class="link-dark text-decoration-none">Rapport Dons</a>
                    </div>
                    <div class="report-description">
                        <i class="bi bi-gift-fill me-1"></i>Analyse des dons reçus
                    </div>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.donations.export', ['from' => $from, 'to' => $to, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                    <a href="{{ route('reports.donations.export', ['from' => $from, 'to' => $to, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="report-row report-row-separated">
            <div class="report-row-body">
                <div class="report-info">
                    <div class="report-title">
                        <a href="{{ route('reports.expenses', ['from' => $from, 'to' => $to]) }}" class="link-dark text-decoration-none">Rapport Dépenses</a>
                    </div>
                    <div class="report-description">
                        <i class="bi bi-receipt-cutoff me-1"></i>Analyse des dépenses effectuées
                    </div>
                </div>
                <div class="report-actions">
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

<script>
function updateReportLinks() {
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    
    // Mettre à jour tous les liens des rapports
    document.querySelectorAll('a[href*="reports.tithes"]').forEach(link => {
        if (link.href.includes('export')) {
            link.href = `{{ route('reports.tithes.export') }}?from=${from}&to=${to}&format=${link.href.includes('pdf') ? 'pdf' : 'excel'}`;
        } else {
            link.href = `{{ route('reports.tithes') }}?from=${from}&to=${to}`;
        }
    });
    
    document.querySelectorAll('a[href*="reports.offerings"]').forEach(link => {
        if (link.href.includes('export')) {
            link.href = `{{ route('reports.offerings.export') }}?from=${from}&to=${to}&format=${link.href.includes('pdf') ? 'pdf' : 'excel'}`;
        } else {
            link.href = `{{ route('reports.offerings') }}?from=${from}&to=${to}`;
        }
    });
    
    document.querySelectorAll('a[href*="reports.donations"]').forEach(link => {
        if (link.href.includes('export')) {
            link.href = `{{ route('reports.donations.export') }}?from=${from}&to=${to}&format=${link.href.includes('pdf') ? 'pdf' : 'excel'}`;
        } else {
            link.href = `{{ route('reports.donations') }}?from=${from}&to=${to}`;
        }
    });
    
    document.querySelectorAll('a[href*="reports.expenses"]').forEach(link => {
        if (link.href.includes('export')) {
            link.href = `{{ route('reports.expenses.export') }}?from=${from}&to=${to}&format=${link.href.includes('pdf') ? 'pdf' : 'excel'}`;
        } else {
            link.href = `{{ route('reports.expenses') }}?from=${from}&to=${to}`;
        }
    });
}

// Mettre à jour les liens quand les dates changent
document.addEventListener('DOMContentLoaded', function() {
    const fromDate = document.getElementById('fromDate');
    const toDate = document.getElementById('toDate');
    
    if (fromDate) {
        fromDate.addEventListener('change', updateReportLinks);
    }
    
    if (toDate) {
        toDate.addEventListener('change', updateReportLinks);
    }
});
</script>
@endsection