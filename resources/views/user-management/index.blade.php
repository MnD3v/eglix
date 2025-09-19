@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- AppBar Gestion des Comptes -->
    <div class="appbar accounts-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Gestion des Comptes</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-shield-check appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">Gérez les utilisateurs et leurs permissions</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('user-management.create') }}" class="appbar-btn-primary">
                    <i class="bi bi-person-plus"></i>
                    <span>Nouvel Utilisateur</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Liste des utilisateurs -->
    <div class="row justify-content-center">
        @forelse($users as $user)
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card user-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar me-3">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="user-status">
                            @if($user->is_active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>

                    <div class="user-info mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Rôle :</span>
                            <span class="fw-bold">{{ $user->role->name ?? 'Aucun' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Type :</span>
                            @if($user->is_church_admin)
                                <span class="badge bg-primary">Administrateur</span>
                            @else
                                <span class="badge bg-info">Utilisateur</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Créé le :</span>
                            <span>{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Permissions -->
                    @if($user->role && $user->role->permissions)
                    <div class="permissions-section mb-3">
                        <h6 class="text-muted mb-2">Permissions :</h6>
                        <div class="permissions-grid">
                            @php
                                $permissionLabels = [
                                    'members' => 'Membres',
                                    'tithes' => 'Dîmes',
                                    'offerings' => 'Offrandes',
                                    'donations' => 'Dons',
                                    'expenses' => 'Dépenses',
                                    'reports' => 'Rapports',
                                    'services' => 'Cultes',
                                    'journal' => 'Journal',
                                    'administration' => 'Administration'
                                ];
                            @endphp
                            
                            @foreach($permissionLabels as $permission => $label)
                                @if(in_array($permission, $user->role->permissions))
                                <div class="permission-item">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <small>{{ $label }}</small>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="{{ route('user-management.edit', $user) }}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="bi bi-pencil-square me-1"></i>
                            Modifier
                        </a>
                        @if(!$user->is_church_admin)
                        <form method="POST" action="{{ route('user-management.destroy', $user) }}" class="flex-fill" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash me-1"></i>
                                Supprimer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Aucun utilisateur trouvé</h3>
                <p class="text-muted">Commencez par créer le premier utilisateur de votre église.</p>
                <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    Créer un utilisateur
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
.accounts-header {
    padding: 1.5rem 0;
}

.accounts-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.accounts-subtitle {
    font-size: 1rem;
    color: #718096;
    margin-bottom: 0;
}

.btn-new-user {
    background-color: #4c51bf;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-new-user:hover {
    background-color: #434190;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.user-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
    background-color: #ffffff;
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.user-avatar {
    width: 46px;
    height: 46px;
    background-color: #ede9fe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6d28d9;
    font-size: 1.4rem;
}

.user-info {
    background-color: #f8fafc;
    border-radius: 8px;
    padding: 0.875rem;
    margin-bottom: 1rem;
}

.permissions-section {
    background-color: #f8fafc;
    border-radius: 8px;
    padding: 0.875rem;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 0.5rem;
}

.permission-item {
    display: flex;
    align-items: center;
    font-size: 0.75rem;
    color: #475569;
}

.permission-item i {
    color: #10b981;
    font-size: 0.875rem;
}

.user-status .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.7rem;
    border-radius: 20px;
    font-weight: 500;
    letter-spacing: 0.3px;
}

.badge.bg-success {
    background-color: #c6f6d5 !important;
    color: #047857 !important;
}

.badge.bg-secondary {
    background-color: #e2e8f0 !important;
    color: #475569 !important;
}

.badge.bg-primary {
    background-color: #ddd6fe !important;
    color: #5b21b6 !important;
}

.badge.bg-info {
    background-color: #bfdbfe !important;
    color: #1d4ed8 !important;
}

.card-footer {
    border-top: 1px solid #f1f5f9;
    padding: 1rem;
    background-color: #ffffff;
}

.btn-outline-primary {
    border-color: #cbd5e1;
    color: #4c51bf;
}

.btn-outline-primary:hover {
    background-color: #eff6ff;
    color: #4c51bf;
    border-color: #4c51bf;
}

.btn-outline-danger {
    border-color: #cbd5e1;
    color: #64748b;
}

.btn-outline-danger:hover {
    background-color: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .permissions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .user-card .card-body {
        padding: 1rem;
    }
    
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .user-management-title {
        font-size: 1.5rem;
    }
}

/* Amélioration de la marge et de l'espacement */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

.user-card {
    margin-bottom: 1rem;
}

/* Styles pour les textes */
.card-title {
    color: #334155;
    font-weight: 600;
    font-size: 1.1rem;
}

.text-muted {
    color: #64748b !important;
}

/* Styles pour les éléments spécifiques */
h6.text-muted {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
</style>

<script>
function confirmDelete() {
    return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');
}

// Test du bouton Nouvel Utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const newUserBtn = document.querySelector('a[href*="user-management/create"]');
    if (newUserBtn) {
        console.log('Bouton Nouvel Utilisateur trouvé:', newUserBtn);
        newUserBtn.addEventListener('click', function(e) {
            console.log('Clic sur le bouton Nouvel Utilisateur détecté');
            // Ne pas empêcher le comportement par défaut
        });
    } else {
        console.log('Bouton Nouvel Utilisateur non trouvé');
    }
});
</script>
@endsection
