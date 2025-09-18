@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-pencil-square me-3"></i>
                    Modifier l'Utilisateur
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-shield-check me-2"></i>
                    Modifiez les informations et permissions de {{ $user->name }}
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
                    <form method="POST" action="{{ route('user-management.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
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
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <label for="role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }} - {{ $role->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                    
                                    $userPermissions = $user->role ? $user->role->permissions : [];
                                @endphp

                                <div class="row">
                                    @foreach($permissions as $permission => $data)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="permissions[]" value="{{ $permission }}" 
                                                   id="perm_{{ $permission }}"
                                                   {{ in_array($permission, $userPermissions) ? 'checked' : '' }}>
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
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Compte actif (l'utilisateur peut se connecter)
                            </label>
                        </div>

                        <!-- Réinitialisation du mot de passe -->
                        @if(!$user->is_church_admin)
                        <div class="section-header mb-4 mt-5">
                            <h5 class="section-title">
                                <i class="bi bi-key me-2"></i>
                                Réinitialisation du Mot de Passe
                            </h5>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Laissez les champs vides si vous ne souhaitez pas modifier le mot de passe.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 6 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        @endif

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('user-management.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                Mettre à jour
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

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.permission-group {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.group-header {
    color: #495057;
    font-size: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.permission-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.permission-item {
    padding: 0.25rem 0;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-size: 0.9rem;
    color: #495057;
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

.alert {
    border-radius: 8px;
    border: none;
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
document.getElementById('role_id').addEventListener('change', function() {
    const roleId = this.value;
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    // Décocher toutes les permissions
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Si un rôle est sélectionné, on pourrait charger les permissions par défaut
    // Pour l'instant, on laisse l'utilisateur choisir manuellement
});

// Validation du mot de passe
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    
    if (password && password.length < 6) {
        e.preventDefault();
        alert('Le mot de passe doit contenir au moins 6 caractères.');
        return false;
    }
});
</script>
@endsection
