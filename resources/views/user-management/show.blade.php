@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-person-circle me-3"></i>
                    Détails de l'Utilisateur
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-shield-check me-2"></i>
                    Informations complètes de {{ $user->name }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('user-management.edit', $user) }}" class="btn btn-light">
                    <i class="bi bi-pencil-square me-2"></i>
                    Modifier
                </a>
                <a href="{{ route('user-management.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <!-- Informations personnelles -->
                    <div class="section-header mb-4">
                        <h5 class="section-title">
                            <i class="bi bi-person me-2"></i>
                            Informations Personnelles
                        </h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nom complet</label>
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Adresse email</label>
                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @if($user->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type de compte</label>
                            <p class="form-control-plaintext">
                                @if($user->is_church_admin)
                                    <span class="badge bg-custom">Administrateur</span>
                                @else
                                    <span class="badge bg-info">Utilisateur</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de création</label>
                            <p class="form-control-plaintext">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dernière mise à jour</label>
                            <p class="form-control-plaintext">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
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
                        <label class="form-label fw-bold">Rôle assigné</label>
                        <p class="form-control-plaintext">
                            {{ $user->role->name ?? 'Aucun rôle' }}
                            @if($user->role)
                                <small class="text-muted d-block">{{ $user->role->description }}</small>
                            @endif
                        </p>
                    </div>

                    @if($user->role && $user->role->permissions)
                    <div class="permissions-section">
                        <h6 class="mb-3">Permissions détaillées :</h6>
                        <div class="permissions-grid">
                            @php
                                $permissionGroups = [
                                    'Membres' => [
                                        'icon' => 'bi-people',
                                        'permissions' => [
                                            'members.create' => 'Créer des membres',
                                            'members.read' => 'Voir les membres',
                                            'members.update' => 'Modifier les membres',
                                            'members.delete' => 'Supprimer les membres'
                                        ]
                                    ],
                                    'Dîmes' => [
                                        'icon' => 'bi-wallet2',
                                        'permissions' => [
                                            'tithes.create' => 'Enregistrer des dîmes',
                                            'tithes.read' => 'Voir les dîmes',
                                            'tithes.update' => 'Modifier les dîmes',
                                            'tithes.delete' => 'Supprimer les dîmes'
                                        ]
                                    ],
                                    'Offrandes' => [
                                        'icon' => 'bi-heart-fill',
                                        'permissions' => [
                                            'offerings.create' => 'Enregistrer des offrandes',
                                            'offerings.read' => 'Voir les offrandes',
                                            'offerings.update' => 'Modifier les offrandes',
                                            'offerings.delete' => 'Supprimer les offrandes'
                                        ]
                                    ],
                                    'Dons' => [
                                        'icon' => 'bi-gift-fill',
                                        'permissions' => [
                                            'donations.create' => 'Enregistrer des dons',
                                            'donations.read' => 'Voir les dons',
                                            'donations.update' => 'Modifier les dons',
                                            'donations.delete' => 'Supprimer les dons'
                                        ]
                                    ],
                                    'Dépenses' => [
                                        'icon' => 'bi-receipt-cutoff',
                                        'permissions' => [
                                            'expenses.create' => 'Enregistrer des dépenses',
                                            'expenses.read' => 'Voir les dépenses',
                                            'expenses.update' => 'Modifier les dépenses',
                                            'expenses.delete' => 'Supprimer les dépenses'
                                        ]
                                    ],
                                    'Rapports' => [
                                        'icon' => 'bi-graph-up',
                                        'permissions' => [
                                            'reports.read' => 'Consulter les rapports'
                                        ]
                                    ],
                                    'Cultes' => [
                                        'icon' => 'bi-calendar-event',
                                        'permissions' => [
                                            'services.create' => 'Créer des cultes',
                                            'services.read' => 'Voir les cultes',
                                            'services.update' => 'Modifier les cultes',
                                            'services.delete' => 'Supprimer les cultes'
                                        ]
                                    ],
                                    'Journal' => [
                                        'icon' => 'bi-journal-text',
                                        'permissions' => [
                                            'journal.create' => 'Créer des entrées',
                                            'journal.read' => 'Voir le journal',
                                            'journal.update' => 'Modifier les entrées',
                                            'journal.delete' => 'Supprimer les entrées'
                                        ]
                                    ],
                                    'Administration' => [
                                        'icon' => 'bi-gear',
                                        'permissions' => [
                                            'administration.create' => 'Créer des fonctions',
                                            'administration.read' => 'Voir l\'administration',
                                            'administration.update' => 'Modifier les fonctions',
                                            'administration.delete' => 'Supprimer les fonctions'
                                        ]
                                    ]
                                ];
                                
                                $userPermissions = $user->role->permissions;
                            @endphp

                            @foreach($permissionGroups as $group => $data)
                                @php
                                    $hasGroupPermission = false;
                                    foreach($data['permissions'] as $permission => $label) {
                                        if(in_array($permission, $userPermissions)) {
                                            $hasGroupPermission = true;
                                            break;
                                        }
                                    }
                                @endphp
                                
                                @if($hasGroupPermission)
                                <div class="permission-group">
                                    <div class="group-header">
                                        <i class="{{ $data['icon'] }} me-2"></i>
                                        <strong>{{ $group }}</strong>
                                    </div>
                                    <div class="permission-list">
                                        @foreach($data['permissions'] as $permission => $label)
                                            @if(in_array($permission, $userPermissions))
                                            <div class="permission-item">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span>{{ $label }}</span>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Aucune permission assignée à cet utilisateur.
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="section-header mb-4 mt-5">
                        <h5 class="section-title">
                            <i class="bi bi-gear me-2"></i>
                            Actions
                        </h5>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn">
                            <i class="bi bi-pencil-square me-2"></i>
                            Modifier l'utilisateur
                        </a>
                        
                        @if(!$user->is_church_admin)
                        <form method="POST" action="{{ route('user-management.destroy', $user) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>
                                Supprimer
                            </button>
                        </form>
                        @endif
                    </div>
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
    display: flex;
    align-items: center;
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

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    line-height: 1.5;
    color: #495057;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
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
@endsection
