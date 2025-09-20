@extends('layouts.app')

@section('content')
<style>
/* Style moderne pour le titre de la page de création de type de fonction */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 0 2rem 0;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.page-header-content {
    position: relative;
    z-index: 2;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title-icon {
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 0.75rem;
    backdrop-filter: blur(10px);
    font-size: 1.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
    margin: 0;
}

.page-actions {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
}

.page-action-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-action-btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    text-decoration: none;
}

/* Responsive design */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
        margin: 0 0 2rem 0;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .page-actions {
        position: static;
        margin-top: 1rem;
        justify-content: center;
        display: flex;
    }
    
    .page-subtitle {
        text-align: center;
    }
}
</style>

<div class="container py-4">
    <!-- En-tête moderne -->
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <div class="page-title-icon">
                    <i class="fas fa-tags"></i>
                </div>
                Créer un type de fonction
            </h1>
            <p class="page-subtitle">Définir un nouveau type de fonction administrative pour l'organisation</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('administration-function-types.index') }}" class="page-action-btn">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>
    <!-- Formulaire moderne -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-soft" style="border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-body" style="padding: 2rem;">
                    <form action="{{ route('administration-function-types.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label">Nom de la fonction <span class="text-danger">*</span></label>
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
                                <textarea name="description" id="description" rows="3" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          placeholder="Description de cette fonction...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sort_order" class="form-label">Ordre d'affichage</label>
                                <input type="number" name="sort_order" id="sort_order" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       value="{{ old('sort_order', 0) }}" 
                                       min="0" step="1">
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
                                        Fonction active
                                    </label>
                                </div>
                                <div class="form-text">Décochez si la fonction n'est plus utilisée</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('administration-function-types.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn">
                                <i class="bi bi-check-lg"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
