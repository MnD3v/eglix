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

/* Styles pour les champs de formulaire tout blanc */
.form-control, .form-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: #ffffff !important;
    color: #000000;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25) !important;
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

/* Container principal en blanc */
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
    border-bottom: 2px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #000000;
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
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
    font-weight: 700 !important;
}

.btn-primary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary i {
    color: #000000 !important;
}

.btn-primary:hover i {
    color: #000000 !important;
}

.btn-secondary {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
    font-weight: 600;
}

.btn-secondary:hover {
    background: #e9ecef;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-secondary i {
    color: #000000 !important;
}

.btn-secondary:hover i {
    color: #000000 !important;
}

/* Styles pour les switches */
.form-check-input {
    border-radius: 6px;
    border: 2px solid #e2e8f0;
}

.form-check-input:checked {
    background-color: #FFCC00;
    border-color: #FFCC00;
}

.form-check-label {
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

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
    
    .container-fluid {
        margin: 10px;
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Créer Église -->
    <div class="appbar churches-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url()->previous() }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Créer une nouvelle église</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('churches.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Section Informations générales -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="bi bi-info-circle me-2"></i>
                Informations générales
            </h3>
            <p class="section-subtitle">Définissez les informations de base de votre église</p>
            
            <div class="form-group mb-3">
                <label for="name" class="form-label">
                    <i class="bi bi-shop me-1"></i>
                    Nom de l'église <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       placeholder="Ex: Église Adventiste de..."
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">
                    <i class="bi bi-text-paragraph me-1"></i>
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          class="form-control @error('description') is-invalid @enderror" 
                          rows="4" 
                          placeholder="Décrivez votre église...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Section Contact -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="bi bi-telephone me-2"></i>
                Informations de contact
            </h3>
            <p class="section-subtitle">Ajoutez les coordonnées de votre église</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1"></i>
                            Téléphone
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" 
                               placeholder="+226 XX XX XX XX">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="eglise@exemple.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="website" class="form-label">
                            <i class="bi bi-globe me-1"></i>
                            Site web
                        </label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               class="form-control @error('website') is-invalid @enderror" 
                               value="{{ old('website') }}" 
                               placeholder="https://www.exemple.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="address" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>
                            Adresse
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Adresse complète de l'église">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-end gap-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>
                Créer l'église
            </button>
        </div>
    </form>
</div>
@endsection
