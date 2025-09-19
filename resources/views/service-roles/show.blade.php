@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('service-roles.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="h3 mb-0" style="color: {{ $serviceRole->color }};">{{ $serviceRole->name }}</h1>
                        <p class="text-muted mb-0">Rôle de culte</p>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ route('service-roles.edit', $serviceRole) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                    <form action="{{ route('service-roles.destroy', $serviceRole) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce rôle ?')">
                            <i class="bi bi-trash me-2"></i>Désactiver
                        </button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Détails du rôle</h5>
                        </div>
                        <div class="card-body">
                            @if($serviceRole->description)
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-muted mb-2">Description</h6>
                                    <p class="mb-0">{{ $serviceRole->description }}</p>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-muted mb-2">Couleur d'identification</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-3" 
                                             style="width: 30px; height: 30px; background-color: {{ $serviceRole->color }}; border: 2px solid {{ $serviceRole->color }};"></div>
                                        <code>{{ $serviceRole->color }}</code>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-muted mb-2">Statut</h6>
                                    <span class="badge {{ $serviceRole->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $serviceRole->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-muted mb-2">Date de création</h6>
                                    <p class="mb-0">{{ $serviceRole->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-muted mb-2">Dernière modification</h6>
                                    <p class="mb-0">{{ $serviceRole->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Statistiques</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="display-6 fw-bold" style="color: {{ $serviceRole->color }};">
                                    {{ $serviceRole->assignments()->count() }}
                                </div>
                                <p class="text-muted mb-0">Affectations</p>
                            </div>
                        </div>
                    </div>

                    @if($serviceRole->assignments()->count() > 0)
                        <div class="card shadow-sm border-0 mt-3">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0">Dernières affectations</h5>
                            </div>
                            <div class="card-body">
                                @foreach($serviceRole->assignments()->with('member', 'service')->latest()->limit(5)->get() as $assignment)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 35px; height: 35px; background-color: {{ $serviceRole->color }}20;">
                                            <i class="bi bi-person" style="color: {{ $serviceRole->color }}; font-size: 14px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $assignment->member->first_name }} {{ $assignment->member->last_name }}</h6>
                                            <small class="text-muted">{{ $assignment->service->date->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

















