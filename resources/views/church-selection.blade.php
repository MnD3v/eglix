@extends('layouts.app')

@section('title', 'Sélection d\'Église')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-shop me-2"></i>
                        Sélectionnez votre église
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($churches->count() > 0)
                        <p class="text-muted mb-4">
                            Vous avez accès à plusieurs églises. Veuillez sélectionner celle avec laquelle vous souhaitez travailler.
                        </p>

                        <div class="row">
                            @foreach($churches as $church)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 church-selection-card" 
                                         onclick="selectChurch({{ $church->id }})"
                                         style="cursor: pointer; transition: all 0.3s ease;">
                                        <div class="card-body text-center">
                                            @if($church->logo)
                                                <img src="{{ $church->logo }}" alt="{{ $church->name }}" 
                                                     class="mb-3" style="max-height: 80px; max-width: 120px;">
                                            @else
                                                <div class="mb-3">
                                                    <i class="bi bi-shop" style="font-size: 3rem; color: #6c757d;"></i>
                                                </div>
                                            @endif
                                            
                                            <h5 class="card-title">{{ $church->name }}</h5>
                                            
                                            @if($church->description)
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($church->description, 100) }}
                                                </p>
                                            @endif
                                            
                                            @if($church->pivot->is_primary)
                                                <span class="badge bg-primary">Église principale</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle" style="font-size: 4rem; color: #f59e0b;"></i>
                            <h4 class="mt-3">Aucune église disponible</h4>
                            <p class="text-muted">
                                Vous n'avez pas accès à une église pour le moment. 
                                Contactez votre administrateur pour obtenir les permissions nécessaires.
                            </p>
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Se déconnecter
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.church-selection-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #007bff;
}

.church-selection-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.church-selection-card:hover {
    border-color: #007bff;
}
</style>

<script>
function selectChurch(churchId) {
    // Afficher un indicateur de chargement
    const cards = document.querySelectorAll('.church-selection-card');
    cards.forEach(card => {
        card.style.opacity = '0.5';
        card.style.pointerEvents = 'none';
    });

    // Envoyer la requête de sélection d'église
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
            // Rediriger vers le dashboard
            window.location.href = '{{ url("/") }}';
        } else {
            // Afficher l'erreur
            alert(data.message || 'Erreur lors de la sélection de l\'église');
            
            // Restaurer l'interface
            cards.forEach(card => {
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la sélection de l\'église');
        
        // Restaurer l'interface
        cards.forEach(card => {
            card.style.opacity = '1';
            card.style.pointerEvents = 'auto';
        });
    });
}
</script>
@endsection
