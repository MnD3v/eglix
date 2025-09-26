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

/* Styles pour les checkboxes */
.form-check-input {
    border-radius: 6px;
    border: 2px solid #e2e8f0;
}

.form-check-input:checked {
    background-color: #FFCC00;
    border-color: #FFCC00;
}

.form-check-label {
    font-weight: 500;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouveau Type de Fonction -->
    <div class="appbar administration-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('administration-function-types.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouveau Type de Fonction</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('administration-function-types.store') }}" method="POST">
                @csrf
                
                <!-- Section Informations de Base -->
                <div class="form-section">
                    <h2 class="section-title">Informations de Base</h2>
                    <p class="section-subtitle">Détails principaux du type de fonction</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Nom de la Fonction</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" 
                                   placeholder="Ex: Ancien, Diacre, Pasteur Assistant..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Décrivez les responsabilités et le rôle de cette fonction...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Paramètres -->
                <div class="form-section">
                    <h2 class="section-title">Paramètres</h2>
                    <p class="section-subtitle">Configuration de l'affichage et du statut</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Ordre d'Affichage</label>
                            <input type="number" name="sort_order" id="sort_order" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', 0) }}" 
                                   min="0" step="1" placeholder="0">
                            <div class="form-text">Plus le nombre est petit, plus la fonction apparaît en haut</div>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="bi bi-check-circle me-2"></i>Fonction active
                                </label>
                            </div>
                            <div class="form-text">Décochez si la fonction n'est plus utilisée</div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Créer le Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
