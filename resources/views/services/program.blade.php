@extends('layouts.app')

@push('scripts')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-[#FF2600]">Programmation du Culte</h1>
                    <p class="text-muted mb-0">{{ $service->date->format('d/m/Y') }} - {{ $service->type ?? 'Culte' }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Retour aux cultes
                    </a>
                    <a href="{{ route('service-roles.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-badge me-2"></i>Gérer les rôles
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Formulaire d'assignation -->
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Assigner un membre</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('services.assign', $service) }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="service_role_id" class="form-label fw-semibold">Rôle *</label>
                                    <select class="form-select @error('service_role_id') is-invalid @enderror" 
                                            id="service_role_id" 
                                            name="service_role_id" 
                                            required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('service_role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="member_id" class="form-label fw-semibold">Membre *</label>
                                    <select class="form-select select2-members @error('member_id') is-invalid @enderror" 
                                            id="member_id" 
                                            name="member_id" 
                                            required>
                                        <option value="">Rechercher un membre</option>
                                        @foreach($members as $member)
                                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                                {{ $member->last_name }} {{ $member->first_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label fw-semibold">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="2" 
                                              placeholder="Notes spécifiques pour cette assignation...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Assigner
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Liste des assignations -->
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0">Assignations actuelles</h5>
                        </div>
                        <div class="card-body">
                            @if($service->assignments->count() > 0)
                                <div class="row">
                                    @foreach($service->assignments as $assignment)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                                 style="width: 40px; height: 40px; background-color: {{ $assignment->serviceRole->color }}20; border: 2px solid {{ $assignment->serviceRole->color }};">
                                                                <i class="bi bi-person" style="color: {{ $assignment->serviceRole->color }};"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0" style="color: {{ $assignment->serviceRole->color }};">
                                                                    {{ $assignment->serviceRole->name }}
                                                                </h6>
                                                                <p class="mb-0 text-muted small">
                                                                    {{ $assignment->member->first_name }} {{ $assignment->member->last_name }}
                                                                </p>
                                                                @if($assignment->notes)
                                                                    <p class="mb-0 text-muted small">
                                                                        <i class="bi bi-chat-text me-1"></i>{{ Str::limit($assignment->notes, 50) }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('service-assignments.destroy', $assignment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir retirer cette assignation ?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-person-badge display-1 text-muted"></i>
                                    <h5 class="mt-3 text-muted">Aucune assignation</h5>
                                    <p class="text-muted">Commencez par assigner des membres aux différents rôles.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Initialisation de Select2 pour la recherche de membres
    $('.select2-members').select2({
        placeholder: 'Rechercher un membre...',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            }
        }
    });
});
</script>
@endsection












