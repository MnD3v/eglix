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
    <!-- AppBar Nouvelle Fonction Administrative -->
    <div class="appbar administration-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('administration.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Fonction Administrative</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('administration.store') }}" method="POST">
                @csrf
                
                <!-- Section Membre et Fonction -->
                <div class="form-section">
                    <h2 class="section-title">Membre et Fonction</h2>
                    <p class="section-subtitle">Sélectionnez le membre et la fonction administrative</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="member_id" class="form-label">Membre</label>
                            <select name="member_id" id="member_id" class="form-select select2-members @error('member_id') is-invalid @enderror" required>
                                <option value="">Rechercher un membre...</option>
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
                            <label for="function_name" class="form-label">Fonction</label>
                            @if(count($functions) > 0)
                                <select name="function_name" id="function_name" class="form-select @error('function_name') is-invalid @enderror" required>
                                    <option value="">Sélectionner une fonction</option>
                                    @foreach($functions as $key => $value)
                                        <option value="{{ $key }}" {{ old('function_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Aucun type de fonction n'a été créé. 
                                    <a href="{{ route('administration-function-types.create') }}" class="alert-link">Créer des types de fonctions</a> d'abord.
                                </div>
                                <input type="text" name="function_name" id="function_name" class="form-control @error('function_name') is-invalid @enderror" 
                                       value="{{ old('function_name') }}" placeholder="Ex: Pasteur, Diacre, Trésorier..." required>
                            @endif
                            @error('function_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Dates -->
                <div class="form-section">
                    <h2 class="section-title">Dates</h2>
                    <p class="section-subtitle">Période d'exercice de la fonction</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Date de Début</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Date de Fin</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date') }}" placeholder="Laisser vide si toujours en cours">
                            <div class="form-text">Laisser vide si la fonction est toujours en cours</div>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Statut -->
                <div class="form-section">
                    <h2 class="section-title">Statut</h2>
                    <p class="section-subtitle">État actuel de la fonction</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="bi bi-check-circle me-2"></i>Fonction active
                                </label>
                            </div>
                            <div class="form-text">Décochez si la fonction est suspendue</div>
                        </div>
                    </div>
                </div>

                <!-- Section Notes -->
                <div class="form-section">
                    <h2 class="section-title">Notes</h2>
                    <p class="section-subtitle">Informations complémentaires sur cette fonction</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="4" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Ajoutez des notes ou commentaires sur cette fonction administrative...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer la Fonction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialisation explicite de Select2 pour les membres
$(document).ready(function() {
    $('.select2-members').select2({
        placeholder: "Rechercher un membre...",
        allowClear: false,
        width: '100%',
        minimumInputLength: 0,
        matcher: function(params, data) {
            // Si aucun terme de recherche, afficher tous les résultats
            if ($.trim(params.term) === '') {
                return data;
            }
            
            // Recherche insensible à la casse
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }
            
            return null;
        },
        language: {
            noResults: function() {
                return "Aucun membre trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            }
        }
    });
});
</script>
@endsection
