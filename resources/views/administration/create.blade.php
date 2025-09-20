@extends('layouts.app')

@section('content')
<style>
/* Style moderne pour le titre de la page d'administration */
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
                    <i class="fas fa-user-cog"></i>
                </div>
                Ajouter une fonction administrative
            </h1>
            <p class="page-subtitle">Assigner une nouvelle fonction administrative à un membre de l'église</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('administration.index') }}" class="page-action-btn">
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
                    <form action="{{ route('administration.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="member_id" class="form-label">Membre <span class="text-danger">*</span></label>
                                <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un membre</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->last_name }} {{ $member->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="function_name" class="form-label">Fonction <span class="text-danger">*</span></label>
                                @if(count($functions) > 0)
                                    <select name="function_name" id="function_name" class="form-select @error('function_name') is-invalid @enderror" required>
                                        <option value="">Sélectionner une fonction</option>
                                        @foreach($functions as $key => $value)
                                            <option value="{{ $key }}" {{ old('function_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Aucun type de fonction n'a été créé. 
                                        <a href="{{ route('administration-function-types.create') }}" class="alert-link">Créer des types de fonctions</a> d'abord.
                                    </div>
                                    <input type="text" name="function_name" id="function_name" class="form-control @error('function_name') is-invalid @enderror" 
                                           value="{{ old('function_name') }}" placeholder="Nom de la fonction" required>
                                @endif
                                @error('function_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Date de fin</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date') }}">
                                <div class="form-text">Laisser vide si la fonction est toujours en cours</div>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Fonction active
                                    </label>
                                </div>
                                <div class="form-text">Décochez si la fonction est suspendue</div>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Notes supplémentaires sur cette fonction...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('administration.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn">
                                <i class="bi bi-check-lg"></i> <span class="btn-text">Enregistrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
