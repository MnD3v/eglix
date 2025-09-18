@extends('layouts.app')

@section('content')
<style>
.administration-card {
    transition: all 0.3s ease;
    border: none;
    background: #ffffff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    border: 1px solid #f1f3f4;
}

.administration-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: #e8f0fe;
}

.status-badge {
    background: #34a853;
    border: none;
    border-radius: 16px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    font-size: 0.75rem;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.bg-success {
    background: #34a853;
}

.status-badge.bg-warning {
    background: #fbbc04;
    color: #1a1a1a;
}

.status-badge.bg-secondary {
    background: #5f6368;
}

.function-title {
    color: #202124;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e8f0fe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    color: #1a73e8;
    margin-right: 12px;
}

.member-info {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.member-name {
    color: #202124;
    font-weight: 600;
    font-size: 0.95rem;
    margin: 0;
}

.member-details {
    color: #5f6368;
    font-size: 0.8rem;
    margin: 2px 0 0 0;
}

.date-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px 12px;
    margin: 8px 0;
    border-left: 3px solid #1a73e8;
}

.date-info i {
    color: #5f6368;
    font-size: 0.85rem;
    margin-right: 6px;
}

.date-text {
    color: #5f6368;
    font-size: 0.8rem;
    font-weight: 500;
}

.notes-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px 12px;
    margin: 8px 0;
    border-left: 3px solid #34a853;
}

.notes-text {
    color: #5f6368;
    font-size: 0.8rem;
    font-style: italic;
    margin: 0;
    line-height: 1.4;
}

.action-buttons {
    opacity: 0;
    transition: all 0.3s ease;
    transform: translateY(8px);
}

.administration-card:hover .action-buttons {
    opacity: 1;
    transform: translateY(0);
}

.action-btn {
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    border: 1px solid;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-primary.action-btn {
    background: #ffffff;
    border-color: #dadce0;
    color: #5f6368;
}

.btn-primary.action-btn:hover {
    background: #f8f9fa;
    border-color: #5f6368;
    color: #202124;
}

.btn-secondary.action-btn {
    background: #1a73e8;
    border-color: #1a73e8;
    color: white;
}

.btn-secondary.action-btn:hover {
    background: #1557b0;
    border-color: #1557b0;
}

.btn-danger.action-btn {
    background: #ffffff;
    border-color: #ea4335;
    color: #ea4335;
}

.btn-danger.action-btn:hover {
    background: #fce8e6;
    border-color: #d33b2c;
    color: #d33b2c;
}

.card-separator {
    height: 1px;
    background: #e8eaed;
    margin: 12px 0;
}
</style>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Administration</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('administration-function-types.index') }}" class="btn btn-outline-primary"><i class="bi bi-tags"></i> <span class="btn-label">Types de fonctions</span></a>
            <a href="{{ route('administration.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> <span class="btn-label">Ajouter une fonction</span></a>
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
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Rechercher par membre, fonction..." name="q" value="{{ $search ?? '' }}">
                    @if(!empty($search))
                    <a class="btn btn-outline-secondary" href="{{ route('administration.index') }}">Effacer</a>
                    @endif
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Fonction</label>
                <select name="function" class="form-select">
                    <option value="">Toutes les fonctions</option>
                    @foreach($availableFunctions as $function)
                        <option value="{{ $function }}" {{ $functionFilter === $function ? 'selected' : '' }}>{{ $function }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted mb-1">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Actives</option>
                    <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactives</option>
                    <option value="ended" {{ $statusFilter === 'ended' ? 'selected' : '' }}>Terminées</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
            </div>
        </div>
    </form>

    <!-- Liste des fonctions -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
        @forelse($functions as $function)
        <div class="col">
            <div class="card card-soft h-100 position-relative administration-card" style="cursor: pointer;" onclick="window.location.href='{{ route('administration.show', $function) }}'">
                <!-- Status indicator -->
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge status-badge
                        @if($function->status === 'Actif') bg-success
                        @elseif($function->status === 'Inactif') bg-warning
                        @else bg-secondary
                        @endif">
                        {{ $function->status }}
                    </span>
                </div>

                <div class="card-body" style="padding: 1.25rem; padding-top: 2.5rem;">
                    <!-- Function name -->
                    <h5 class="function-title">
                        {{ $function->function_name }}
                    </h5>
                    
                    <!-- Member info with avatar -->
                    <div class="member-info">
                        <div class="member-avatar">
                            {{ substr($function->member->first_name, 0, 1) }}{{ substr($function->member->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="member-name">{{ $function->member->last_name }} {{ $function->member->first_name }}</p>
                            <p class="member-details">{{ $function->member->email ?? 'Aucun email' }}</p>
                            @if($function->member->phone)
                                <p class="member-details">
                                    <i class="bi bi-telephone me-1"></i>{{ $function->member->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Date info -->
                    <div class="date-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar3"></i>
                            <span class="date-text">Depuis le {{ $function->start_date->format('d/m/Y') }}</span>
                        </div>
                        
                        @if($function->end_date)
                            <div class="d-flex align-items-center mt-1">
                                <i class="bi bi-calendar-event"></i>
                                <span class="date-text" style="color: #ea4335;">Jusqu'au {{ $function->end_date->format('d/m/Y') }}</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center mt-1">
                                <i class="bi bi-infinity"></i>
                                <span class="date-text" style="color: #34a853;">En cours</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Notes -->
                    @if($function->notes)
                        <div class="notes-section">
                            <p class="notes-text">"{{ Str::limit($function->notes, 60) }}"</p>
                        </div>
                    @endif
                    
                    <!-- Separator -->
                    <div class="card-separator"></div>
                </div>
                
                <!-- Action buttons -->
                <div class="card-footer bg-transparent border-0 action-buttons" style="padding: 0.75rem 1.25rem;">
                    <div class="d-flex gap-2">
                        <a class="btn btn-secondary action-btn" href="{{ route('administration.edit', $function) }}" onclick="event.stopPropagation()">
                            <i class="bi bi-gear"></i>Gérer
                        </a>
                        <form action="{{ route('administration.destroy', $function) }}" method="POST" data-confirm="Supprimer cette fonction ?" data-confirm-ok="Supprimer" style="display: inline;" onclick="event.stopPropagation()">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger action-btn" type="submit">
                                <i class="bi bi-trash"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center text-muted py-5">
                <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3">Aucune fonction administrative</p>
                <a href="{{ route('administration.create') }}" class="btn btn-primary">Ajouter la première fonction</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $functions->links() }}
    </div>
</div>
@endsection
