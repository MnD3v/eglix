@extends('layouts.app')
@section('content')
<div class="container py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Culte du {{ \Carbon\Carbon::parse($service->date)->format('d/m/Y') }}</h1>
            @if($service->type)
                <span class="badge bg-primary">{{ $service->type }}</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ url()->previous() }}">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a class="btn btn-outline-primary" href="{{ route('services.program', $service) }}">
                <i class="bi bi-person-badge me-1"></i>Programmer
            </a>
            <a class="btn btn-outline-secondary" href="{{ route('services.edit', $service) }}">Modifier</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations générales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <strong>Thème:</strong> {{ $service->theme ?? '—' }}
                        </div>
                        <div class="col-12">
                            <strong>Heure:</strong> 
                            @if($service->start_time && $service->end_time)
                                {{ $service->start_time }} - {{ $service->end_time }}
                            @elseif($service->start_time)
                                {{ $service->start_time }}
                            @else
                                —
                            @endif
                        </div>
                        <div class="col-12">
                            <strong>Lieu:</strong> {{ $service->location ?? '—' }}
                        </div>
                        @if($service->notes)
                        <div class="col-12">
                            <strong>Notes:</strong> 
                            <div class="mt-1 p-2 bg-light rounded">{{ $service->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignations des rôles -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Rôles assignés</h5>
                </div>
                <div class="card-body">
                    @if($service->assignments->count() > 0)
                        <div class="row g-2">
                            @foreach($service->assignments as $assignment)
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 36px; height: 36px; background-color: {{ $assignment->serviceRole->color }}20; border: 2px solid {{ $assignment->serviceRole->color }};">
                                            <i class="bi bi-person" style="color: {{ $assignment->serviceRole->color }}; font-size: 14px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold" style="color: {{ $assignment->serviceRole->color }};">
                                                {{ $assignment->serviceRole->name }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $assignment->member->first_name }} {{ $assignment->member->last_name }}
                                            </div>
                                            @if($assignment->notes)
                                                <div class="text-muted small">
                                                    <i class="bi bi-chat-text me-1"></i>{{ $assignment->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-person-badge display-4 text-muted"></i>
                            <p class="text-muted mt-2">Aucun rôle assigné</p>
                            <a href="{{ route('services.program', $service) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Assigner des rôles
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


