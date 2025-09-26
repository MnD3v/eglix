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

/* Styles pour les permissions */
.permissions-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.permissions-list .form-check {
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.permissions-list .form-check:hover {
    border-color: #FFCC00;
    box-shadow: 0 2px 8px rgba(255, 204, 0, 0.15);
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouvel Utilisateur -->
    <div class="appbar accounts-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('user-management.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvel Utilisateur</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('user-management.store') }}" autocomplete="off">
                @csrf
                
                <!-- Champ caché pour empêcher l'auto-complétion -->
                <input type="text" style="display:none" name="fake_username" autocomplete="username">
                <input type="password" style="display:none" name="fake_password" autocomplete="current-password">
                
                <!-- Section Informations Personnelles -->
                <div class="form-section">
                    <h2 class="section-title">Informations Personnelles</h2>
                    <p class="section-subtitle">Détails de base sur le nouvel utilisateur</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom Complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: Jean Dupont">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   autocomplete="new-email" required placeholder="Ex: jean.dupont@eglise.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Mot de Passe -->
                <div class="form-section">
                    <h2 class="section-title">Mot de Passe</h2>
                    <p class="section-subtitle">Définissez un mot de passe sécurisé</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de Passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" autocomplete="new-password" required placeholder="Minimum 6 caractères">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 6 caractères</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le Mot de Passe</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   autocomplete="new-password" required placeholder="Répétez le mot de passe">
                        </div>
                    </div>
                </div>

                <!-- Section Rôle -->
                <div class="form-section">
                    <h2 class="section-title">Rôle</h2>
                    <p class="section-subtitle">Définissez le rôle de l'utilisateur dans l'église</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="role_name" class="form-label">Rôle</label>
                            <input type="text" class="form-control @error('role_name') is-invalid @enderror" 
                                   id="role_name" name="role_name" value="{{ old('role_name') }}" 
                                   placeholder="Ex: Secrétaire, Trésorier, Pasteur..." required>
                            @error('role_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nom du rôle de l'utilisateur dans l'église</div>
                        </div>
                    </div>
                </div>

                <!-- Section Permissions -->
                <div class="form-section">
                    <h2 class="section-title">Permissions</h2>
                    <p class="section-subtitle">Sélectionnez les permissions à accorder à cet utilisateur</p>
                    
                    <div class="permissions-section">
                        <div class="permissions-list">
                            @php
                                $permissions = [
                                    'members' => ['label' => 'Membres', 'icon' => 'people'],
                                    'tithes' => ['label' => 'Dîmes', 'icon' => 'wallet2'],
                                    'offerings' => ['label' => 'Offrandes', 'icon' => 'heart-fill'],
                                    'donations' => ['label' => 'Dons', 'icon' => 'gift-fill'],
                                    'expenses' => ['label' => 'Dépenses', 'icon' => 'receipt-cutoff'],
                                    'reports' => ['label' => 'Rapports', 'icon' => 'graph-up'],
                                    'services' => ['label' => 'Cultes', 'icon' => 'calendar-event'],
                                    'journal' => ['label' => 'Journal', 'icon' => 'journal-text'],
                                    'administration' => ['label' => 'Administration', 'icon' => 'gear']
                                ];
                            @endphp

                            <div class="row">
                                @foreach($permissions as $permission => $data)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" value="{{ $permission }}" 
                                               id="perm_{{ $permission }}"
                                               {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission }}">
                                            <i class="bi bi-{{ $data['icon'] }} me-2"></i>
                                            {{ $data['label'] }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Statut -->
                <div class="form-section">
                    <h2 class="section-title">Statut du Compte</h2>
                    <p class="section-subtitle">Définissez l'état du compte utilisateur</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <i class="bi bi-check-circle me-2"></i>Compte actif (l'utilisateur peut se connecter)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary" id="createUserBtn">
                        <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Créer l'Utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserBtn').closest('form');
    const submitBtn = document.getElementById('createUserBtn');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-check-lg me-2" style="color: #000000;"></i>Création en cours...';
    });
});
</script>
@endsection
