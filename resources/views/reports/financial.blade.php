@extends('layouts.app')
@section('content')

<style>
/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6B7280;
    font-size: 1rem;
    margin-bottom: 0;
}

/* Summary Cards */
.summary-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.summary-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.summary-icon.month { background: #FF2600; }
.summary-icon.year { background: #22C55E; }

.summary-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0;
}

.summary-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #6B7280;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 600;
    color: #1F2937;
}

/* Table */
.table-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.table-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.table-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #8B5CF6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.table-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern th {
    background: #F9FAFB;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.table-modern td {
    padding: 0.75rem;
    border-bottom: 1px solid #F3F4F6;
    color: #1F2937;
    font-size: 0.875rem;
}

.table-modern tr:hover {
    background: #F9FAFB;
}

.table-modern tr:last-child td {
    border-bottom: none;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-bottom: 2rem;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn.primary {
    background: #FF2600;
    color: white;
    border-color: #FF2600;
}

.action-btn.primary:hover {
    background: #E52200;
    border-color: #E52200;
    color: white;
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Rapport Financier</h1>
        <p class="page-subtitle">Vue d'ensemble des finances de l'église</p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('reports.index') }}" class="action-btn">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
        <a href="{{ route('reports.financial.pdf') }}" class="action-btn primary">
            <i class="bi bi-download"></i>
            Télécharger PDF
        </a>
    </div>

    <div class="row g-3">
        <!-- Mois en cours -->
        <div class="col-lg-6">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-icon month">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <h3 class="summary-title">Mois en cours</h3>
                </div>
                <ul class="summary-list">
                    <li class="summary-item">
                        <span class="summary-label">Dîmes</span>
                        <span class="summary-value">{{ number_format(round($month['tithes']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Offrandes</span>
                        <span class="summary-value">{{ number_format(round($month['offerings']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Dons</span>
                        <span class="summary-value">{{ number_format(round($month['donations']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Dépenses</span>
                        <span class="summary-value">{{ number_format(round($month['expenses']), 0, ',', ' ') }} FCFA</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Année en cours -->
        <div class="col-lg-6">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-icon year">
                        <i class="bi bi-calendar-year"></i>
                    </div>
                    <h3 class="summary-title">Année en cours</h3>
                </div>
                <ul class="summary-list">
                    <li class="summary-item">
                        <span class="summary-label">Dîmes</span>
                        <span class="summary-value">{{ number_format(round($year['tithes']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Offrandes</span>
                        <span class="summary-value">{{ number_format(round($year['offerings']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Dons</span>
                        <span class="summary-value">{{ number_format(round($year['donations']), 0, ',', ' ') }} FCFA</span>
                    </li>
                    <li class="summary-item">
                        <span class="summary-label">Dépenses</span>
                        <span class="summary-value">{{ number_format(round($year['expenses']), 0, ',', ' ') }} FCFA</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Par projet -->
    <div class="table-card">
        <div class="table-header">
            <div class="table-icon">
                <i class="bi bi-kanban"></i>
            </div>
            <h3 class="table-title">Par projet</h3>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Projet</th>
                        <th>Dons</th>
                        <th>Dépenses</th>
                        <th>Solde</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($byProject as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ number_format(round($p->donations_total), 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format(round($p->expenses_total), 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format(round($p->donations_total - $p->expenses_total), 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucune donnée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


