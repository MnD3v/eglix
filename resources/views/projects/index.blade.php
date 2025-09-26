@extends('layouts.app')
@section('content')
<style>
/* Styles pour la liste des projets */
.projects-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.project-row {
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

.project-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.project-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.project-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.project-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.project-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.project-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.project-budget {
    margin: 4px 0;
}

.budget-value {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.project-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.project-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.project-row-empty i {
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

/* Icônes noires dans toute la section projets */
.projects-list .bi,
.projects-appbar .bi,
.project-details .bi,
.project-row-empty .bi,
.search-icon .bi,
.search-btn .bi {
    color: #000000 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Projets -->
    <div class="appbar projects-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Projets</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('projects.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouveau projet</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <!-- Barre de recherche -->
    <form method="GET" class="mb-3">
        <div class="row g-2 g-lg-3 align-items-end">
            <div class="col-12 col-lg-6">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par nom de projet..." name="q" value="{{ $search ?? '' }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
        </div>
    </form>

    <div class="projects-list">
        @forelse($projects as $index => $p)
            <div class="project-row {{ $index > 0 ? 'project-row-separated' : '' }}">
                <div class="project-row-body">
                    <div class="project-info">
                        <div class="project-name">
                            <a href="{{ route('projects.show', $p) }}" class="link-dark text-decoration-none">{{ $p->name }}</a>
                        </div>
                        <div class="project-budget">
                            <div class="budget-value">{{ number_format($p->budget ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="project-details">
                            <span class="badge bg-{{ match($p->status){ 'planned' => 'secondary', 'in_progress' => 'primary', 'completed' => 'success', 'cancelled' => 'danger', default => 'secondary' } }}">{{ str_replace('_',' ', $p->status) }}</span>
                            @if($p->start_date || $p->end_date)
                                <span class="ms-2"><i class="bi bi-calendar3 me-1"></i>{{ optional($p->start_date)->format('d/m/Y') }} @if($p->end_date) – {{ optional($p->end_date)->format('d/m/Y') }} @endif</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="project-actions">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('projects.edit', $p) }}">Modifier</a>
                    <form action="{{ route('projects.destroy', $p) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="project-row-empty">
                <i class="bi bi-kanban"></i>
                <div>Aucun projet trouvé</div>
                <small class="text-muted mt-2">Commencez par créer votre premier projet</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
</div>
@endsection


