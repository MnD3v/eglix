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

/* Styles pour les champs de formulaire */
.form-control, .form-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff !important;
    color: #000000;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
    background: #ffffff !important;
    color: #000000;
}

.form-label {
    font-weight: 600;
    color: #000000;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les sections du formulaire */
.form-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Container principal */
.container-fluid {
    background: #ffffff;
    border-radius: 20px;
    margin: 20px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #FFCC00;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* Styles pour les boutons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: #FFCC00;
    color: #000000;
    border: 1px solid #FFCC00;
}

.btn-primary:hover {
    background: #e6b800;
    color: #000000;
    border: 1px solid #e6b800;
}

.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
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

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar -->
    <div class="appbar projects-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('projects.show', $project) }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Activité</h1>
                    <div class="appbar-subtitle">
                        <span class="appbar-subtitle-text">Projet: {{ $project->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('projects.activities.store', $project) }}">
        @csrf
        
        <!-- Section Informations de l'activité -->
        <div class="form-section">
            <h2 class="section-title">📋 Informations de l'Activité</h2>
            <p class="section-subtitle">Détails sur l'activité réalisée dans le cadre du projet</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Titre de l'Activité</label>
                    <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required placeholder="Ex: Formation des jeunes, Achat de matériel...">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Montant Dépensé (FCFA)</label>
                    <input name="amount_spent" type="number" step="0.01" min="0" value="{{ old('amount_spent') }}" class="form-control @error('amount_spent') is-invalid @enderror" required placeholder="Ex: 50000">
                    @error('amount_spent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Date de l'Activité</label>
                    <input name="activity_date" type="date" value="{{ old('activity_date', now()->format('Y-m-d')) }}" class="form-control @error('activity_date') is-invalid @enderror" required>
                    @error('activity_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Décrivez en détail l'activité réalisée, les objectifs atteints, les bénéficiaires...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Maximum 1000 caractères</div>
                </div>
            </div>
        </div>

        <!-- Résumé du projet -->
        <div class="form-section">
            <h2 class="section-title">📊 Contexte du Projet</h2>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div class="info-label" style="font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Budget Total</div>
                        <div class="info-value" style="font-size: 1.1rem; font-weight: 700; color: #000000;">{{ number_format($project->budget, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div class="info-label" style="font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Statut</div>
                        <div class="info-value" style="font-size: 1.1rem; font-weight: 700; color: #000000;">
                            @if($project->status === 'active')
                                <span style="color: #059669;">🟢 Actif</span>
                            @elseif($project->status === 'completed')
                                <span style="color: #0891b2;">✅ Terminé</span>
                            @else
                                <span style="color: #dc2626;">⏸️ Suspendu</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($project->description)
                <div class="col-md-12">
                    <div class="info-card" style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div class="info-label" style="font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 600;">Description du Projet</div>
                        <div class="info-value" style="font-size: 0.9rem; color: #374151; line-height: 1.5;">{{ $project->description }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Enregistrer l'Activité
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
