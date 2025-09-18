@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Détails du type de fonction</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('administration-function-types.edit', $administrationFunctionType) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            @include('partials.back-button')
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Informations générales</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom</label>
                                <p class="mb-0">{{ $administrationFunctionType->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Statut</label>
                                <p class="mb-0">
                                    <span class="badge {{ $administrationFunctionType->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $administrationFunctionType->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Ordre d'affichage</label>
                                <p class="mb-0">#{{ $administrationFunctionType->sort_order }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Statistiques</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total des fonctions</label>
                                <p class="mb-0">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $administrationFunctionType->functions_count }} membre{{ $administrationFunctionType->functions_count > 1 ? 's' : '' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Fonctions actives</label>
                                <p class="mb-0">
                                    <i class="bi bi-check-circle me-1 text-success"></i>
                                    {{ $administrationFunctionType->active_functions_count }} membre{{ $administrationFunctionType->active_functions_count > 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>

                        @if($administrationFunctionType->description)
                        <div class="col-12">
                            <h5 class="text-muted mb-3">Description</h5>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $administrationFunctionType->description }}</p>
                            </div>
                        </div>
                        @endif

                        @if($administrationFunctionType->functions->count() > 0)
                        <div class="col-12">
                            <h5 class="text-muted mb-3">Membres avec cette fonction</h5>
                            <div class="list-group">
                                @foreach($administrationFunctionType->functions as $function)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>
                                            <a href="{{ route('members.show', $function->member) }}" class="link-dark text-decoration-none">
                                                {{ $function->member->last_name }} {{ $function->member->first_name }}
                                            </a>
                                        </strong>
                                        <br>
                                        <small class="text-muted">
                                            Depuis le {{ $function->start_date->format('d/m/Y') }}
                                            @if($function->end_date)
                                                jusqu'au {{ $function->end_date->format('d/m/Y') }}
                                            @else
                                                (en cours)
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge 
                                        @if($function->status === 'Actif') bg-success
                                        @elseif($function->status === 'Inactif') bg-warning
                                        @else bg-secondary
                                        @endif">
                                        {{ $function->status }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Créé le {{ $administrationFunctionType->created_at->format('d/m/Y à H:i') }}
                        @if($administrationFunctionType->updated_at != $administrationFunctionType->created_at)
                            • Modifié le {{ $administrationFunctionType->updated_at->format('d/m/Y à H:i') }}
                        @endif
                    </small>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('administration-function-types.edit', $administrationFunctionType) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form action="{{ route('administration-function-types.destroy', $administrationFunctionType) }}" method="POST" data-confirm="Supprimer ce type de fonction ?" data-confirm-ok="Supprimer">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
