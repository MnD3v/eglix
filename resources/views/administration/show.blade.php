@extends('layouts.app')

@section('content')
<style>
.detail-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    overflow: hidden;
}

.section-title {
    color: #5f6368;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e8f0fe;
}

.info-item {
    margin-bottom: 1.25rem;
}

.info-label {
    color: #5f6368;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.info-value {
    color: #202124;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

.status-badge {
    background: #34a853;
    color: white;
    border: none;
    border-radius: 16px;
    padding: 0.4rem 0.8rem;
    font-weight: 600;
    font-size: 0.75rem;
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

.info-icon {
    color: #5f6368;
    font-size: 0.9rem;
    margin-right: 0.5rem;
}

.notes-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border-left: 4px solid #1a73e8;
    margin-top: 1rem;
}

.notes-text {
    color: #5f6368;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
    font-style: italic;
}

.card-footer {
    background: #f8f9fa;
    border-top: 1px solid #e8eaed;
    padding: 1rem 1.5rem;
}

.footer-info {
    color: #5f6368;
    font-size: 0.8rem;
}

.action-btn {
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    border: 1px solid;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-secondary.action-btn {
    background: #ffffff;
    border-color: #dadce0;
    color: #5f6368;
}

.btn-secondary.action-btn:hover {
    background: #f8f9fa;
    border-color: #5f6368;
    color: #202124;
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

.back-btn {
    background: #ffffff;
    border-color: #dadce0;
    color: #5f6368;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.back-btn:hover {
    background: #f8f9fa;
    border-color: #5f6368;
    color: #202124;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
</style>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-2" style="color: #202124; font-weight: 600;">Détails de la fonction administrative</h1>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Informations détaillées sur la fonction administrative</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('administration.edit', $administration) }}" class="action-btn btn-secondary">
                        <i class="bi bi-gear"></i> Gérer
                    </a>
                    <a href="{{ url()->previous() }}" class="back-btn">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card detail-card">
                <div class="card-body" style="padding: 2rem;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="section-title">Informations générales</h5>
                            
                            <div class="info-item">
                                <div class="info-label">Fonction</div>
                                <p class="info-value">{{ $administration->function_name }}</p>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Membre</div>
                                <p class="info-value">
                                    <a href="{{ route('members.show', $administration->member) }}" class="link-dark text-decoration-none">
                                        {{ $administration->member->last_name }} {{ $administration->member->first_name }}
                                    </a>
                                </p>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Statut</div>
                                <p class="info-value">
                                    <span class="badge status-badge
                                        @if($administration->status === 'Actif') bg-success
                                        @elseif($administration->status === 'Inactif') bg-warning
                                        @else bg-secondary
                                        @endif">
                                        {{ $administration->status }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="section-title">Période</h5>
                            
                            <div class="info-item">
                                <div class="info-label">Date de début</div>
                                <p class="info-value">
                                    <i class="bi bi-calendar-event info-icon"></i>
                                    {{ $administration->start_date->format('d/m/Y') }}
                                </p>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Date de fin</div>
                                <p class="info-value">
                                    @if($administration->end_date)
                                        <i class="bi bi-calendar-x info-icon" style="color: #ea4335;"></i>
                                        {{ $administration->end_date->format('d/m/Y') }}
                                    @else
                                        <i class="bi bi-infinity info-icon" style="color: #34a853;"></i>
                                        <span style="color: #34a853;">En cours</span>
                                    @endif
                                </p>
                            </div>

                            @if($administration->end_date)
                            <div class="info-item">
                                <div class="info-label">Durée</div>
                                <p class="info-value">
                                    <i class="bi bi-clock info-icon"></i>
                                    {{ $administration->duration }} jour{{ $administration->duration > 1 ? 's' : '' }}
                                </p>
                            </div>
                            @endif
                        </div>

                        @if($administration->notes)
                        <div class="col-12">
                            <div class="notes-section">
                                <div class="info-label">Notes</div>
                                <p class="notes-text">{{ $administration->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="footer-info">
                        Créé le {{ $administration->created_at->format('d/m/Y à H:i') }}
                        @if($administration->updated_at != $administration->created_at)
                            • Modifié le {{ $administration->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </small>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('administration.edit', $administration) }}" class="action-btn btn-secondary">
                            <i class="bi bi-gear"></i> Gérer
                        </a>
                        <form action="{{ route('administration.destroy', $administration) }}" method="POST" data-confirm="Supprimer cette fonction ?" data-confirm-ok="Supprimer" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn btn-danger" type="submit">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
