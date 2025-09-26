@extends('layouts.app')

@section('content')
<style>
/* Styles pour la liste des fonctions d'administration */
.administration-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.administration-row {
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

.administration-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.administration-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.administration-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.administration-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.administration-date {
    margin-bottom: 4px;
}

.administration-title {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.administration-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.administration-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.administration-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.administration-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
.search-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-icon {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 25px 0 0 25px;
    color: #000000;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-left: none;
    border-right: none;
    background-color: #ffffff;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 14px;
}

.search-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
    background-color: #ffffff;
}

.search-btn {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 25px 25px 0;
    color: #000000;
    font-weight: 600;
    padding: 12px 20px;
}

.search-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #000000;
}

.filter-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.filter-select:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

/* Icônes noires dans toute la section administration */
.administration-list .bi,
.administration-appbar .bi,
.administration-details .bi,
.administration-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.administration-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Administration -->
    <div class="appbar administration-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Gestion des Fonctions</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('administration-function-types.index') }}" class="appbar-btn-white me-2">
                    <i class="bi bi-tags"></i>
                    <span class="btn-text">Types de fonctions</span>
                </a>
                <a href="{{ route('administration.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Nouvelle fonction</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-primary" style="padding: 1.25rem;">
                <div class="kpi-header" style="margin-bottom: 0.75rem;">
                    <div class="kpi-icon" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Total</h3>
                        <p class="kpi-description" style="font-size: 0.8rem; margin-bottom: 0;">Fonctions</p>
                    </div>
                </div>
                <div class="kpi-meta">
                    <div class="kpi-value" style="font-size: 1.75rem;">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-success" style="padding: 1.25rem;">
                <div class="kpi-header" style="margin-bottom: 0.75rem;">
                    <div class="kpi-icon" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Actives</h3>
                        <p class="kpi-description" style="font-size: 0.8rem; margin-bottom: 0;">En cours</p>
                    </div>
                </div>
                <div class="kpi-meta">
                    <div class="kpi-value" style="font-size: 1.75rem;">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-warning" style="padding: 1.25rem;">
                <div class="kpi-header" style="margin-bottom: 0.75rem;">
                    <div class="kpi-icon" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Inactives</h3>
                        <p class="kpi-description" style="font-size: 0.8rem; margin-bottom: 0;">Suspendues</p>
                    </div>
                </div>
                <div class="kpi-meta">
                    <div class="kpi-value" style="font-size: 1.75rem;">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-info" style="padding: 1.25rem;">
                <div class="kpi-header" style="margin-bottom: 0.75rem;">
                    <div class="kpi-icon" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title" style="font-size: 1rem; margin-bottom: 0.25rem;">Terminées</h3>
                        <p class="kpi-description" style="font-size: 0.8rem; margin-bottom: 0;">Finies</p>
                    </div>
                </div>
                <div class="kpi-meta">
                    <div class="kpi-value" style="font-size: 1.75rem;">{{ $stats['ended'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par membre, fonction..." name="q" value="{{ $search ?? '' }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Fonction</label>
                <select name="function" class="form-select filter-select">
                    <option value="">Toutes les fonctions</option>
                    @foreach($availableFunctions as $function)
                        <option value="{{ $function }}" {{ $functionFilter === $function ? 'selected' : '' }}>{{ $function }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="status" class="form-select filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Actives</option>
                    <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactives</option>
                    <option value="ended" {{ $statusFilter === 'ended' ? 'selected' : '' }}>Terminées</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn filter-btn w-100" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
            </div>
        </div>
    </form>

    <!-- Liste des fonctions -->
    <div class="administration-list">
        @forelse($functions as $index => $function)
            <div class="administration-row {{ $index > 0 ? 'administration-row-separated' : '' }}">
                <div class="administration-row-body">
                    <div class="administration-info">
                        <div class="administration-date">
                            <span class="badge bg-custom">{{ $function->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="administration-title">
                            <a href="{{ route('administration.show', $function) }}" class="link-dark text-decoration-none">{{ $function->function_name }}</a>
                        </div>
                        <div class="administration-details">
                            <i class="bi bi-person me-1"></i>{{ $function->member->last_name }} {{ $function->member->first_name }}
                            <span class="ms-2"><i class="bi bi-envelope me-1"></i>{{ $function->member->email ?? 'Aucun email' }}</span>
                            @if($function->member->phone)
                                <span class="ms-2"><i class="bi bi-telephone me-1"></i>{{ $function->member->phone }}</span>
                            @endif
                            @if($function->status === 'Actif')
                                <span class="ms-2"><i class="bi bi-check-circle me-1"></i>Actif</span>
                            @elseif($function->status === 'Inactif')
                                <span class="ms-2"><i class="bi bi-pause-circle me-1"></i>Inactif</span>
                            @else
                                <span class="ms-2"><i class="bi bi-x-circle me-1"></i>Terminé</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="administration-actions">
                    <a href="{{ route('administration.edit', $function) }}" class="btn btn-sm btn-outline-primary">Gérer</a>
                    <form action="{{ route('administration.destroy', $function) }}" method="POST" data-confirm="Supprimer cette fonction ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="administration-row-empty">
                <i class="bi bi-person-badge"></i>
                <div>Aucune fonction administrative</div>
                <small class="text-muted mt-2">Commencez par ajouter la première fonction</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $functions->links() }}
    </div>
</div>
@endsection
