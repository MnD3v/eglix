@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header d'inscription -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="bi bi-church-fill text-primary" style="font-size: 3rem;"></i>
                </div>
                <h1 class="h2 text-primary">{{ $church->name }}</h1>
                <p class="text-muted">Inscription individuelle</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Remplissez le formulaire ci-dessous pour vous inscrire en tant que membre de cette église.
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Formulaire d'inscription -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.register.process', ['token' => $token]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Informations de base -->
                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Adresse</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informations personnelles -->
                            <div class="col-md-4">
                                <label class="form-label">Genre</label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Homme</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Situation familiale</label>
                                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Célibataire</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Marié(e)</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Veuf/Veuve</option>
                                </select>
                                @error('marital_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Informations spirituelles -->
                            <div class="col-md-6">
                                <label class="form-label">Date de baptême</label>
                                <input type="date" name="baptized_at" class="form-control @error('baptized_at') is-invalid @enderror" 
                                       value="{{ old('baptized_at') }}">
                                @error('baptized_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Responsable du baptême</label>
                                <input type="text" name="baptism_responsible" class="form-control @error('baptism_responsible') is-invalid @enderror" 
                                       value="{{ old('baptism_responsible') }}">
                                @error('baptism_responsible')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Date d'adhésion</label>
                                <input type="date" name="joined_at" class="form-control @error('joined_at') is-invalid @enderror" 
                                       value="{{ old('joined_at', now()->format('Y-m-d')) }}">
                                @error('joined_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Photo de profil</label>
                                <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" 
                                       accept="image/*">
                                <small class="form-text text-muted">Formats acceptés: JPG, PNG, WebP (max 4MB)</small>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>S'inscrire
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
