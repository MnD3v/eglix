@extends('layouts.app')

@section('content')
<style>
/* Background avec grid léger */
body {
    background: #f5f5f5 !important;
    background-image: 
        linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
    background-size: 20px 20px;
}

/* Container principal en blanc */
.container-fluid {
    background: #ffffff;
    border-radius: 20px;
    margin: 20px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Styles pour les cartes */
.info-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #FFCC00;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    align-items: center;
    justify-content: between;
}

/* Grille de statistiques */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* Cartes d'activités */
.activity-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.activity-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.activity-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #000000;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.activity-amount {
    font-size: 1rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 0.5rem;
}

.activity-date {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0.75rem;
}

.activity-description {
    font-size: 0.875rem;
    color: #4a5568;
    line-height: 1.5;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #64748b;
}

.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Badges de statut */
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active { background: #d1fae5; color: #065f46; }
.status-completed { background: #dbeafe; color: #1e40af; }
.status-planned { background: #fef3c7; color: #92400e; }
.status-cancelled { background: #fee2e2; color: #991b1b; }
.status-in_progress { background: #e0f2fe; color: #0277bd; }

/* Informations du projet */
.project-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}

.info-label {
    font-size: 0.8rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1rem;
    color: #000000;
    font-weight: 500;
}

/* AppBar styling */
.appbar {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.appbar-title {
    color: #000000;
}

.appbar-back-btn {
    color: #000000;
}

/* Icônes des cartes d'activités en noir */
.activity-card .btn i {
    color: #000000 !important;
}

.activity-card .btn-outline-primary i {
    color: #000000 !important;
}

.activity-card .btn-outline-danger i {
    color: #000000 !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .project-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar -->
    <div class="appbar projects-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('projects.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">{{ $project->name }}</h1>
                    @if($project->description)
                        <div class="appbar-subtitle">
                            <span class="appbar-subtitle-text">{{ Str::limit($project->description, 60) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('projects.activities.create', $project) }}" class="appbar-btn-yellow me-2">
                    <i class="bi bi-plus-circle"></i>
                    <span class="btn-text">Nouvelle Activité</span>
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="appbar-btn-white">
                    <i class="bi bi-pencil"></i>
                    <span class="btn-text">Modifier</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques du projet -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($project->budget ?? 0, 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Budget Total</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $project->activities_count }}</div>
            <div class="stat-label">Activités Réalisées</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ number_format($project->total_activities_spent, 0, ',', ' ') }} FCFA</div>
            <div class="stat-label">Total Dépensé</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">
                <span class="status-badge status-{{ $project->status }}">
                    @if($project->status === 'active')
                        🟢 Actif
                    @elseif($project->status === 'completed')
                        ✅ Terminé
                    @elseif($project->status === 'planned')
                        📋 Planifié
                    @elseif($project->status === 'in_progress')
                        🔄 En cours
                    @else
                        ❌ Annulé
                    @endif
                </span>
            </div>
            <div class="stat-label">Statut</div>
        </div>
    </div>

    <!-- Informations détaillées du projet -->
    <div class="info-card">
        <h2 class="section-title">📋 Informations du Projet</h2>
        
        <div class="project-info-grid">
            @if($project->description)
            <div class="info-item">
                <div class="info-label">Description</div>
                <div class="info-value">{{ $project->description }}</div>
            </div>
            @endif
            
            @if($project->start_date)
            <div class="info-item">
                <div class="info-label">Date de Début</div>
                <div class="info-value">{{ $project->start_date->format('d/m/Y') }}</div>
            </div>
            @endif
            
            @if($project->end_date)
            <div class="info-item">
                <div class="info-label">Date de Fin</div>
                <div class="info-value">{{ $project->end_date->format('d/m/Y') }}</div>
            </div>
            @endif
            
            <div class="info-item">
                <div class="info-label">Budget Restant</div>
                <div class="info-value">{{ number_format(($project->budget ?? 0) - $project->total_activities_spent, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- Section des activités -->
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">📋 Activités Réalisées</h2>
            <a href="{{ route('projects.activities.create', $project) }}" class="appbar-btn-yellow">
                <i class="bi bi-plus-circle"></i>
                <span class="btn-text">Nouvelle Activité</span>
            </a>
        </div>

        @forelse($project->activities()->orderByActivityDate()->get() as $activity)
            <div class="activity-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="activity-title">{{ $activity->title }}</div>
                        <div class="activity-amount">{{ number_format($activity->amount_spent, 0, ',', ' ') }} FCFA</div>
                        <div class="activity-date">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $activity->activity_date->format('d/m/Y') }}
                        </div>
                        @if($activity->description)
                            <div class="activity-description">{{ $activity->description }}</div>
                        @endif
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('projects.activities.edit', [$project, $activity]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('projects.activities.destroy', [$project, $activity]) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h4>Aucune activité enregistrée</h4>
                <p class="mb-3">Commencez par ajouter une activité réalisée dans le cadre de ce projet.</p>
                <a href="{{ route('projects.activities.create', $project) }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-circle"></i>
                    <span class="btn-text">Première Activité</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection


