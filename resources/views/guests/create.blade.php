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
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25) !important;
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
@media (max-width:。768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouvel Invité -->
    <div class="appbar guests-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('guests.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvel Invité</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('guests.store') }}">
        @csrf
        
        <!-- Section Informations Personnelles -->
        <div class="form-section">
            <h2 class="section-title">Informations Personnelles</h2>
            <p class="section-subtitle">Les informations de base de l'invité</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required placeholder="Ex: Jean">
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required placeholder="Ex: Dupont">
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: +237 6XX XXX XXX">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Adresse</label>
                    <input name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Ex: Quartier Bastos, Yaoundé">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Informations de Visite -->
        <div class="form-section">
            <h2 class="section-title">Informations de Visite</h2>
            <p class="section-subtitle">Détails sur la visite de l'invité</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Date de Visite</label>
                    <input type="date" name="visit_date" value="{{ old('visit_date', now()->format('Y-m-d')) }}" class="form-control @error('visit_date') is-invalid @enderror" required>
                    @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Origine</label>
                    <select name="origin" class="form-select @error('origin') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach(\App\Models\Guest::getOriginTypes() as $key => $label)
                            <option value="{{ $key }}" @selected(old('origin') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('origin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Informations Spirituelles -->
        <div class="form-section">
            <h2 class="section-title">Informations Spirituelles</h2>
            <p class="section-subtitle">Détails sur le parcours spirituel de l'invité</p>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Église d'Origine</label>
                    <input name="church_background" value="{{ old('church_background') }}" class="form-control @error('church_background') is-invalid @enderror" placeholder="Ex: Église XYZ de Douala">
                    @error('church_background')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut de Visite</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(\App\Models\Guest::getStatusTypes() as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', 'visit_1') == $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Notes Générales</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Notes complémentaires sur l'invité...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Enregistrer l'Invité
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
