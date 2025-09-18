@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête de page -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-people-fill me-3"></i>
                    Gestion des Comptes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-shield-check me-2"></i>
                    Gérez les utilisateurs et leurs permissions
                </p>
            </div>
           
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('user-management.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-person-plus-fill me-2"></i>
            <span class="d-none d-md-inline">Nouvel Utilisateur</span>
        </a>
        <button type="button" class="btn btn-outline-light btn-lg" onclick="window.location.href='{{ route('user-management.create') }}'">
            <i class="bi bi-plus-circle me-2"></i>
            <span class="d-none d-md-inline">Test</span>
        </button>
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
.user-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.user-info {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 10px;
    padding: 1rem;
}

.permissions-section {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 10px;
    padding: 1rem;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.5rem;
}

.permission-item {
    display: flex;
    align-items: center;
    font-size: 0.8rem;
}

.user-status .badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

.card-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1rem;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
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
}

/* Amélioration de la marge et de l'espacement */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.user-card {
    margin-bottom: 1.5rem;
}

/* Styles pour le bouton Nouvel Utilisateur */
.btn.btn-light {
    background-color: #ffffff !important;
    border: 1px solid #dee2e6 !important;
    color: #495057 !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    cursor: pointer !important;
    display: inline-block !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 0.5rem !important;
}

.btn.btn-light:hover {
    background-color: #f8f9fa !important;
    border-color: #adb5bd !important;
    color: #495057 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    text-decoration: none !important;
}

.btn.btn-light:focus {
    outline: none !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

.btn.btn-light:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
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
