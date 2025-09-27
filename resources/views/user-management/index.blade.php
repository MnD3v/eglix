@extends('layouts.app')

@section('content')
<style>
/* Styles pour la liste des comptes */
.accounts-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.account-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.account-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.account-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.account-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.account-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.account-date {
    margin-bottom: 4px;
}

.account-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.account-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.account-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.account-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.account-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
.search-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-icon {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 25px 0 0 25px;
    color: #000000;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-left: none;
    border-right: none;
    background-color: #ffffff;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 14px;
}

.search-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
    background-color: #ffffff;
}

.search-btn {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 25px 25px 0;
    color: #000000;
    font-weight: 600;
    padding: 12px 20px;
}

.search-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #000000;
}

.filter-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.filter-select:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

/* Icônes noires dans toute la section comptes */
.accounts-list .bi,
.accounts-appbar .bi,
.account-details .bi,
.account-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.account-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container-fluid px-4 py-4" >
    <!-- AppBar Gestion des Comptes -->
    <div class="appbar accounts-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Gestion des Comptes</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('user-management.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Nouvel utilisateur</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Champ de recherche -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par nom, email..." name="q" value="{{ request('q') }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
        </div>
    </form>

    <!-- Liste des utilisateurs -->
    <div class="accounts-list">
        @forelse($users as $index => $user)
            <div class="account-row {{ $index > 0 ? 'account-row-separated' : '' }}">
                <div class="account-row-body">
                    <div class="account-info">
                        <div class="account-date">
                            <span class="badge bg-custom">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="account-name">
                            {{ $user->name }}
                        </div>
                        <div class="account-details">
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                            <span class="ms-2"><i class="bi bi-person-badge me-1"></i>{{ $user->role->name ?? 'Aucun rôle' }}</span>
                            @if($user->is_church_admin)
                                <span class="ms-2"><i class="bi bi-shield-check me-1"></i>Administrateur</span>
                            @else
                                <span class="ms-2"><i class="bi bi-person me-1"></i>Utilisateur</span>
                            @endif
                            @if($user->is_active)
                                <span class="ms-2"><i class="bi bi-check-circle me-1"></i>Actif</span>
                            @else
                                <span class="ms-2"><i class="bi bi-x-circle me-1"></i>Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="account-actions">
                    <a href="{{ route('user-management.edit', $user) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                    @if(!$user->is_church_admin)
                        <form action="{{ route('user-management.destroy', $user) }}" method="POST" onsubmit="return confirmDelete()" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="account-row-empty">
                <i class="bi bi-people"></i>
                <div>Aucun utilisateur trouvé</div>
                <small class="text-muted mt-2">Commencez par créer le premier utilisateur de votre église</small>
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

.badge.bg-custom {
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
    
    /* Responsive pour les cartes de comptes */
    .account-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        padding: 1rem;
    }
    
    .account-row-body {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.75rem;
    }
    
    .account-info {
        width: 100%;
        text-align: center;
    }
    
    .account-name {
        font-size: 15px;
        word-break: break-word;
    }
    
    .account-details {
        justify-content: center;
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .account-actions {
        flex-direction: row;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .account-actions .btn {
        font-size: 12px;
        padding: 6px 12px;
        min-width: 80px;
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
