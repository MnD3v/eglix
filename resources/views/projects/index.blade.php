@extends('layouts.app')
@section('content')
<style>
/* Styles pour la liste des projets */
.projects-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.project-row {
    background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    padding: 24px 28px;
    gap: 24px;
    min-height: 100px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
}

.project-row::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #FFCC00 0%, #FFD700 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-row:hover::before {
    opacity: 1;
}

.project-row-separated {
    margin-top: 0;
}

.project-row:hover {
    background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
    border-color: #e2e8f0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.project-row-body {
    display: flex;
    gap: 24px;
    align-items: center;
    flex: 1;
}

.project-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #FFCC00 0%, #FFD700 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(255, 204, 0, 0.25);
}

.project-icon i {
    font-size: 24px;
    color: #000000;
}

.project-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.project-name {
    font-size: 18px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
    letter-spacing: -0.02em;
}

.project-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.project-name a:hover {
    color: #FFCC00;
}

.project-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.project-budget {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.budget-icon {
    width: 20px;
    height: 20px;
    background: #FFCC00;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.budget-icon i {
    font-size: 10px;
    color: #000000;
}

.budget-value {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.project-details {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
}

.project-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
    letter-spacing: 0.02em;
}

.status-planned {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.status-in_progress {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-cancelled {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.project-date {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    color: #64748b;
}

.project-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.project-actions .btn {
    border-radius: 12px;
    font-weight: 600;
    font-size: 13px;
    padding: 8px 16px;
    transition: all 0.2s ease;
    border-width: 1.5px;
}

.project-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.project-actions .btn-outline-primary {
    background: #ffffff;
    color: #FFCC00;
    border-color: #FFCC00;
}

.project-actions .btn-outline-primary:hover {
    background: #FFCC00;
    color: #000000;
    border-color: #FFCC00;
}

.project-actions .btn-outline-secondary {
    background: #ffffff;
    color: #64748b;
    border-color: #e2e8f0;
}

.project-actions .btn-outline-secondary:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
}

.project-actions .btn-outline-danger {
    background: #ffffff;
    color: #dc2626;
    border-color: #fecaca;
}

.project-actions .btn-outline-danger:hover {
    background: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
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

/* Responsive design */
@media (max-width: 768px) {
    .project-row {
        padding: 20px 16px;
        gap: 16px;
        flex-direction: column;
        align-items: stretch;
    }
    
    .project-row-body {
        gap: 16px;
    }
    
    .project-icon {
        width: 48px;
        height: 48px;
    }
    
    .project-icon i {
        font-size: 20px;
    }
    
    .project-name {
        font-size: 16px;
    }
    
    .project-meta {
        gap: 12px;
    }
    
    .project-actions {
        justify-content: space-between;
        gap: 8px;
        margin-top: 8px;
    }
    
    .project-actions .btn {
        flex: 1;
        font-size: 12px;
        padding: 6px 12px;
    }
}

@media (max-width: 480px) {
    .project-row {
        padding: 16px 12px;
    }
    
    .project-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .project-actions {
        flex-direction: column;
        gap: 8px;
    }
    
    .project-actions .btn {
        width: 100%;
    }
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
            <div class="project-row">
                <div class="project-row-body">
                    <!-- Icône du projet -->
                    <div class="project-icon">
                        <i class="bi bi-{{ match($p->status){ 'planned' => 'calendar-check', 'in_progress' => 'gear-fill', 'completed' => 'check-circle-fill', 'cancelled' => 'x-circle-fill', default => 'kanban' } }}"></i>
                    </div>
                    
                    <div class="project-info">
                        <div class="project-name">
                            <a href="{{ route('projects.show', $p) }}">{{ $p->name }}</a>
                        </div>
                        
                        <div class="project-meta">
                            <!-- Budget avec icône -->
                            <div class="project-budget">
                                <div class="budget-icon">
                                    <i class="bi bi-currency-exchange"></i>
                                </div>
                                <div class="budget-value">{{ number_format($p->budget ?? 0, 0, ',', ' ') }} FCFA</div>
                            </div>
                            
                            <!-- Statut -->
                            <div class="project-status status-{{ $p->status }}">
                                <i class="bi bi-{{ match($p->status){ 'planned' => 'clock', 'in_progress' => 'arrow-clockwise', 'completed' => 'check-circle', 'cancelled' => 'x-circle', default => 'question-circle' } }}"></i>
                                {{ match($p->status){ 'planned' => 'Planifié', 'in_progress' => 'En cours', 'completed' => 'Terminé', 'cancelled' => 'Annulé', default => 'Inconnu' } }}
                            </div>
                            
                            <!-- Dates -->
                            @if($p->start_date || $p->end_date)
                                <div class="project-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ optional($p->start_date)->format('d/m/Y') }}
                                    @if($p->end_date) – {{ optional($p->end_date)->format('d/m/Y') }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="project-actions">
                    <a class="btn btn-outline-primary" href="{{ route('projects.activities.create', $p) }}" title="Ajouter une activité">
                        <i class="bi bi-plus-circle me-1"></i>Activité
                    </a>
                    <a class="btn btn-outline-secondary" href="{{ route('projects.edit', $p) }}" title="Modifier le projet">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form action="{{ route('projects.destroy', $p) }}" method="POST" data-confirm="Supprimer ce projet ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger" title="Supprimer le projet">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
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


