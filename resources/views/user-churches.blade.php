@extends('layouts.app')

@section('title', 'Mes Églises')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-shop me-2"></i>
                        Mes Églises
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChurchModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Ajouter une Église
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($userChurches->count() > 0)
                        <div class="row">
                            @foreach($userChurches as $userChurch)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 church-card {{ $userChurch->pivot->is_primary ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    @if($userChurch->logo)
                                                        <img src="{{ $userChurch->logo }}" alt="{{ $userChurch->name }}" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                    @else
                                                        <div class="me-3 d-flex align-items-center justify-content-center bg-light rounded" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="bi bi-shop text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $userChurch->name }}</h6>
                                                        @if($userChurch->pivot->is_primary)
                                                            <small class="text-primary fw-bold">Église Principale</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if(!$userChurch->pivot->is_primary)
                                                            <li>
                                                                <a class="dropdown-item" href="#" 
                                                                   onclick="setPrimaryChurch({{ $userChurch->id }})">
                                                                    <i class="bi bi-star me-2"></i>Définir comme Principale
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#" 
                                                               onclick="switchToChurch({{ $userChurch->id }})">
                                                                <i class="bi bi-arrow-right-circle me-2"></i>Basculer vers cette Église
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" 
                                                               onclick="removeChurch({{ $userChurch->id }})">
                                                                <i class="bi bi-trash me-2"></i>Retirer l'Accès
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            @if($userChurch->description)
                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($userChurch->description, 100) }}
                                                </p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="small text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Ajouté le {{ $userChurch->pivot->created_at->format('d/m/Y') }}
                                                </div>
                                                
                                                @if($userChurch->id == get_current_church_id())
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Actuelle
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-shop" style="font-size: 4rem; color: #6c757d;"></i>
                            <h4 class="mt-3">Aucune église assignée</h4>
                            <p class="text-muted">
                                Vous n'avez pas encore d'église assignée. Contactez votre administrateur 
                                ou ajoutez une église si vous en avez les permissions.
                            </p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChurchModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                Ajouter une Église
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une église -->
<div class="modal fade" id="addChurchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>
                    Ajouter une Église
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addChurchForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="churchSelect" class="form-label">Sélectionner une Église</label>
                        <select class="form-select" id="churchSelect" name="church_id" required>
                            <option value="">Choisir une église...</option>
                            @foreach($availableChurches as $church)
                                <option value="{{ $church->id }}">{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isPrimary" name="is_primary">
                            <label class="form-check-label" for="isPrimary">
                                Définir comme église principale
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.church-card {
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.church-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.church-card.border-primary {
    border-color: #007bff !important;
}

.church-card .card-body {
    padding: 1.25rem;
}
</style>

<script>
// Ajouter une église
document.getElementById('addChurchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const churchId = formData.get('church_id');
    const isPrimary = formData.get('is_primary') === 'on';
    
    fetch('{{ route("user.churches.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            church_id: churchId,
            is_primary: isPrimary
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de l\'ajout de l\'église');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de l\'ajout de l\'église');
    });
});

// Basculer vers une église
function switchToChurch(churchId) {
    fetch('{{ route("church.switch") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            church_id: churchId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors du changement d\'église');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du changement d\'église');
    });
}

// Définir comme église principale
function setPrimaryChurch(churchId) {
    if (confirm('Êtes-vous sûr de vouloir définir cette église comme principale ?')) {
        fetch('{{ route("user.churches.set-primary") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                church_id: churchId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la modification');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la modification');
        });
    }
}

// Retirer l'accès à une église
function removeChurch(churchId) {
    if (confirm('Êtes-vous sûr de vouloir retirer l\'accès à cette église ?')) {
        fetch('{{ route("user.churches.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                church_id: churchId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection
