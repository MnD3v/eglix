@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-[#FF2600]">Rôles de Culte</h1>
                    <p class="text-muted mb-0">Personnalisez les rôles selon les besoins de votre église</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="bi bi-question-circle me-2"></i>Aide
                    </button>
                    <form action="{{ route('service-roles.reset-defaults') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning" 
                                onclick="return confirm('Êtes-vous sûr de vouloir restaurer les rôles par défaut ? Cela désactivera tous les rôles actuels.')">
                            <i class="bi bi-arrow-clockwise me-2"></i>Rôles par défaut
                        </button>
                    </form>
                    <a href="{{ route('service-roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Rôle
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="filter" id="active" checked>
                        <label class="btn btn-outline-primary" for="active">Actifs</label>
                        
                        <input type="radio" class="btn-check" name="filter" id="inactive">
                        <label class="btn btn-outline-secondary" for="inactive">Inactifs</label>
                        
                        <input type="radio" class="btn-check" name="filter" id="all">
                        <label class="btn btn-outline-info" for="all">Tous</label>
                    </div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3" id="roles-container">
                @forelse($roles as $role)
                    <div class="col role-card" data-status="{{ $role->is_active ? 'active' : 'inactive' }}">
                        <div class="card h-100 shadow-sm border-0 {{ !$role->is_active ? 'opacity-75' : '' }}">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px; background-color: {{ $role->color }}20; border: 2px solid {{ $role->color }};">
                                        <i class="bi bi-person-badge" style="color: {{ $role->color }};"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-0" style="color: {{ $role->color }};">{{ $role->name }}</h5>
                                        @if(!$role->is_active)
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($role->description)
                                    <p class="card-text text-muted small">{{ Str::limit($role->description, 100) }}</p>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $role->created_at->format('d/m/Y') }}
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('service-roles.show', $role) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('service-roles.edit', $role) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($role->is_active)
                                            <form action="{{ route('service-roles.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce rôle ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('service-roles.update', $role) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $role->name }}">
                                                <input type="hidden" name="description" value="{{ $role->description }}">
                                                <input type="hidden" name="color" value="{{ $role->color }}">
                                                <input type="hidden" name="is_active" value="1">
                                                <button type="submit" class="btn btn-outline-success btn-sm" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir réactiver ce rôle ?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-person-badge display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">Aucun rôle de culte</h4>
                            <p class="text-muted">Commencez par créer votre premier rôle de culte.</p>
                            <a href="{{ route('service-roles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Créer un rôle
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($roles->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('input[name="filter"]');
    const roleCards = document.querySelectorAll('.role-card');
    
    function filterRoles(status) {
        roleCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all' || cardStatus === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            if (this.checked) {
                filterRoles(this.id);
            }
        });
    });
});
</script>

<!-- Modal d'aide -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="bi bi-question-circle me-2"></i>Guide des Rôles de Culte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">🎯 Qu'est-ce qu'un rôle ?</h6>
                        <p class="small">Un rôle définit une fonction spécifique dans le culte (MC, prédicateur, lecteur, etc.). Chaque église peut avoir ses propres rôles selon ses traditions.</p>
                        
                        <h6 class="text-primary">🎨 Personnalisation</h6>
                        <p class="small">Chaque rôle a une couleur unique pour faciliter l'identification dans la programmation des cultes.</p>
                        
                        <h6 class="text-primary">✅ Statut actif/inactif</h6>
                        <p class="small">Les rôles inactifs n'apparaissent pas dans la création de culte mais restent visibles pour référence.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">🔄 Rôles par défaut</h6>
                        <p class="small">Le bouton "Rôles par défaut" restaure une liste standard de rôles communs aux églises.</p>
                        
                        <h6 class="text-primary">📝 Utilisation</h6>
                        <p class="small">Une fois créés, les rôles apparaissent dans le formulaire de création de culte pour assigner les membres.</p>
                        
                        <h6 class="text-primary">💡 Conseils</h6>
                        <ul class="small">
                            <li>Créez des rôles spécifiques à votre église</li>
                            <li>Utilisez des couleurs distinctes</li>
                            <li>Ajoutez des descriptions claires</li>
                            <li>Désactivez les rôles non utilisés</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

