@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Nouveau culte</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('services.store') }}" class="card p-4">
        @csrf
        
        <!-- Informations générales du culte -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="bi bi-calendar-event me-2"></i>Informations générales</h5>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="form-control @error('date') is-invalid @enderror" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Heure début</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="form-control @error('start_time') is-invalid @enderror">
                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Heure fin</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" class="form-control @error('end_time') is-invalid @enderror">
                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Lieu</label>
                <input name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="Ex: Temple principal">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label">Thème du culte</label>
                <input name="theme" value="{{ old('theme') }}" class="form-control @error('theme') is-invalid @enderror" placeholder="Ex: L'amour de Dieu">
                @error('theme')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Type de culte</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">Sélectionner...</option>
                    <option value="Culte dominical" {{ old('type') == 'Culte dominical' ? 'selected' : '' }}>Culte dominical</option>
                    <option value="Culte de prière" {{ old('type') == 'Culte de prière' ? 'selected' : '' }}>Culte de prière</option>
                    <option value="Culte spécial" {{ old('type') == 'Culte spécial' ? 'selected' : '' }}>Culte spécial</option>
                    <option value="Culte de jeûne" {{ old('type') == 'Culte de jeûne' ? 'selected' : '' }}>Culte de jeûne</option>
                    <option value="Culte de réveil" {{ old('type') == 'Culte de réveil' ? 'selected' : '' }}>Culte de réveil</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Notes supplémentaires sur le culte...">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <!-- Assignation des rôles -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Assignation des rôles</h5>
                <p class="text-muted small">Assignez les membres aux différents rôles pour ce culte. Vous pourrez modifier ces assignations plus tard.</p>
            </div>
            
            @php
                $roles = \App\Models\ServiceRole::where('is_active', true)->orderBy('name')->get();
                $members = \App\Models\Member::where('church_id', auth()->user()->church_id)
                    ->where('status', 'active')
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->get();
            @endphp

            @foreach($roles as $role)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                                 style="width: 32px; height: 32px; background-color: {{ $role->color }}20; border: 2px solid {{ $role->color }};">
                                <i class="bi bi-person" style="color: {{ $role->color }}; font-size: 14px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0" style="color: {{ $role->color }};">{{ $role->name }}</h6>
                                <small class="text-muted">{{ $role->description }}</small>
                            </div>
                        </div>
                        <select name="assignments[{{ $role->id }}][member_id]" class="form-select form-select-sm">
                            <option value="">Non assigné</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old("assignments.{$role->id}.member_id") == $member->id ? 'selected' : '' }}>
                                    {{ $member->last_name }} {{ $member->first_name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="assignments[{{ $role->id }}][service_role_id]" value="{{ $role->id }}">
                        <textarea name="assignments[{{ $role->id }}][notes]" class="form-control form-control-sm mt-2" rows="1" placeholder="Notes...">{{ old("assignments.{$role->id}.notes") }}</textarea>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>Créer le culte
            </button>
        </div>
    </form>
</div>
@endsection


