@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-person-plus-fill me-3"></i>
                    Nouvel Utilisateur
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-shield-check me-2"></i>
                    Créez un compte utilisateur avec des permissions spécifiques
                </p>
            </div>
            <a href="{{ route('user-management.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-2"></i>
                Retour
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('user-management.store') }}" autocomplete="off">
                        @csrf
                        
                        <!-- Champ caché pour empêcher l'auto-complétion -->
                        <input type="text" style="display:none" name="fake_username" autocomplete="username">
                        <input type="password" style="display:none" name="fake_password" autocomplete="current-password">
                        
                        <!-- Informations personnelles -->
                        <div class="section-header mb-4">
                            <h5 class="section-title">
                                <i class="bi bi-person me-2"></i>
                                Informations Personnelles
                            </h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       autocomplete="new-email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" autocomplete="new-password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 6 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       autocomplete="new-password" required>
                            </div>
                        </div>

                        <!-- Rôle et permissions -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="section-title">
                                <i class="bi bi-shield-check me-2"></i>
                                Rôle et Permissions
                            </h5>
                        </div>

                        <div class="mb-4">
                            <label for="role_name" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('role_name') is-invalid @enderror" 
                                   id="role_name" name="role_name" value="{{ old('role_name') }}" 
                                   placeholder="Ex: Secrétaire, Trésorier, Pasteur..." required>
                            @error('role_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nom du rôle de l'utilisateur dans l'église</div>
                        </div>

                        <!-- Permissions détaillées -->
                        <div class="permissions-section">
                            <h6 class="mb-3">Sélectionnez les permissions à accorder :</h6>
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

                        <!-- Statut -->
                        <div class="section-header mb-4 mt-5">
                            <h5 class="section-title">
                                <i class="bi bi-toggle-on me-2"></i>
                                Statut du Compte
                            </h5>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Compte actif (l'utilisateur peut se connecter)
                            </label>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('user-management.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                Créer l'utilisateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.section-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin: 0;
}

.permissions-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.permissions-list {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
}

.permissions-list .row {
    margin: 0;
}

.permissions-list .col-md-6,
.permissions-list .col-lg-4 {
    padding: 0.25rem 0.5rem;
}

.form-check {
    padding: 0.5rem;
    background: white;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.form-check:hover {
    border-color: #667eea;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.1);
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-size: 0.9rem;
    color: #495057;
    cursor: pointer;
    margin-left: 0.5rem;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
}

@media (max-width: 768px) {
    .permissions-grid {
        grid-template-columns: 1fr;
    }
    
    .permission-group {
        padding: 0.75rem;
    }
}
</style>

<script>
// Sélection automatique des permissions basée sur le rôle
document.addEventListener('DOMContentLoaded', function() {
    const roleNameInput = document.getElementById('role_name');
    if (roleNameInput) {
        roleNameInput.addEventListener('input', function() {
            const roleName = this.value.toLowerCase();
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            
            // Si le rôle contient "admin" ou "administrateur", cocher toutes les permissions
            if (roleName.includes('admin') || roleName.includes('administrateur')) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            }
        });
    }
});
</script>
@endsection
