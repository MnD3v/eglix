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
    background: #22C55E;
    color: white;
    border-color: #22C55E;
}

.action-btn.primary:hover {
    background: #16A34A;
    border-color: #16A34A;
    color: white;
}

/* Report Cards */
.report-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.report-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.report-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.report-icon {
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

.report-icon.monthly { background: #22C55E; }
.report-icon.members { background: #8B5CF6; }

.report-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0;
}

.report-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.report-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.report-item:last-child {
    border-bottom: none;
}

.report-label {
    color: #6B7280;
    font-size: 0.875rem;
}

.report-value {
    font-weight: 600;
    color: #1F2937;
}

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: #6B7280;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Rapport Mensuel</h1>
        <p class="page-subtitle">Détail des dîmes par mois et par membre</p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('reports.index') }}" class="action-btn">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
        <a href="{{ route('reports.monthly.pdf') }}" class="action-btn primary">
            <i class="bi bi-download"></i>
            Télécharger PDF
        </a>
    </div>

    <div class="row g-3">
        <!-- Par mois -->
        <div class="col-lg-6">
            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon monthly">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <h3 class="report-title">Par mois</h3>
                </div>
                @if($rows->count() > 0)
                    <ul class="report-list">
                        @foreach($rows as $r)
                        <li class="report-item">
                            <span class="report-label">{{ $r->month }}</span>
                            <span class="report-value">{{ number_format(round($r->total), 0, ',', ' ') }} FCFA</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
    </div>

        <!-- Par membre -->
        <div class="col-lg-6">
            <div class="report-card">
                <div class="report-header">
                    <div class="report-icon members">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="report-title">Par membre</h3>
                </div>
                @if($perMember->count() > 0)
                    <ul class="report-list">
                        @foreach($perMember as $m)
                        <li class="report-item">
                            <span class="report-label">{{ $m->member }} ({{ $m->month }})</span>
                            <span class="report-value">{{ number_format(round($m->total), 0, ',', ' ') }} FCFA</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="bi bi-person-x"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection