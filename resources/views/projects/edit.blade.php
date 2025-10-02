@extends('layouts.app')

@section('content')
<style>
/* Styles pour les champs de formulaire arrondis */
.form-control, .form-select, .form-label {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les sections du formulaire */
.form-section {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
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

.btn-primary,
.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
    font-weight: 700 !important;
}

.btn-primary:hover,
.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary i,
.btn-outline-secondary i {
    color: #000000 !important;
}

.btn-primary:hover i,
.btn-outline-secondary:hover i {
    color: #000000 !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Projet -->
    <div class="appbar projects-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('projects.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier Projet</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('projects.update', $project) }}">
        @csrf
        @method('PUT')
        
        <!-- Section Informations du Projet -->
        <div class="form-section">
            <h2 class="section-title">Informations du Projet</h2>
            <p class="section-subtitle">Détails sur le projet à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom du Projet</label>
                    <input name="name" value="{{ old('name', $project->name) }}" class="form-control @error('name') is-invalid @enderror" required placeholder="Ex: Construction de l'église">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['planned'=>'Planifié','in_progress'=>'En cours','completed'=>'Terminé','cancelled'=>'Annulé'] as $k=>$v)
                            <option value="{{ $k }}" @selected(old('status', $project->status)==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Dates et Budget -->
        <div class="form-section">
            <h2 class="section-title">Dates et Budget</h2>
            <p class="section-subtitle">Planning et budget du projet</p>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date de Début</label>
                    <input type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}" class="form-control @error('start_date') is-invalid @enderror">
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date de Fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}" class="form-control @error('end_date') is-invalid @enderror">
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Budget (FCFA)</label>
                    <input type="number" step="0.01" name="budget" value="{{ old('budget', $project->budget) }}" class="form-control @error('budget') is-invalid @enderror" placeholder="Ex: 1000000">
                    @error('budget')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="form-section">
            <h2 class="section-title">Description</h2>
            <p class="section-subtitle">Description détaillée du projet</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Décrivez le projet en détail...">{{ old('description', $project->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection


