@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center">
                <!-- Icône de succès -->
                <div class="mb-4">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                </div>
                
                <!-- Message de succès -->
                <h1 class="h2 text-success mb-3">Inscription réussie !</h1>
                <p class="lead text-muted mb-4">
                    Bienvenue dans l'église <strong>{{ $church->name }}</strong>
                </p>
                
                <!-- Informations -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            Prochaines étapes
                        </h5>
                        <ul class="list-unstyled text-start">
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                Votre inscription a été enregistrée avec succès
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-person-check text-primary me-2"></i>
                                Vous êtes maintenant membre de l'église {{ $church->name }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                L'équipe de l'église vous contactera bientôt
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                Vous recevrez les informations sur les prochains événements
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Informations de contact de l'église -->
                @if($church->phone || $church->email)
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            Contact de l'église
                        </h6>
                        <div class="row g-2">
                            @if($church->phone)
                            <div class="col-12">
                                <small class="text-muted">Téléphone:</small><br>
                                <a href="tel:{{ $church->phone }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $church->phone }}
                                </a>
                            </div>
                            @endif
                            @if($church->email)
                            <div class="col-12">
                                <small class="text-muted">Email:</small><br>
                                <a href="mailto:{{ $church->email }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-1"></i>{{ $church->email }}
                                </a>
                            </div>
                            @endif
                            @if($church->address)
                            <div class="col-12">
                                <small class="text-muted">Adresse:</small><br>
                                <i class="bi bi-geo-alt me-1"></i>{{ $church->address }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Actions -->
                <div class="mt-4">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: bounceIn 0.6s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
@endsection
