@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Modifier culte</h1>
    <form method="POST" action="{{ route('services.update', $service) }}" class="card p-3">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ old('date', optional($service->date)->format('Y-m-d')) }}" class="form-control @error('date') is-invalid @enderror" required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Heure début</label>
                <input type="time" name="start_time" value="{{ old('start_time', $service->start_time) }}" class="form-control @error('start_time') is-invalid @enderror">
                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Heure fin</label>
                <input type="time" name="end_time" value="{{ old('end_time', $service->end_time) }}" class="form-control @error('end_time') is-invalid @enderror">
                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Lieu</label>
                <input name="location" value="{{ old('location', $service->location) }}" class="form-control @error('location') is-invalid @enderror">
                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Thème</label>
                <input name="theme" value="{{ old('theme', $service->theme) }}" class="form-control @error('theme') is-invalid @enderror">
                @error('theme')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Type de culte</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">Sélectionner...</option>
                    <option value="Culte dominical" {{ old('type', $service->type) == 'Culte dominical' ? 'selected' : '' }}>Culte dominical</option>
                    <option value="Culte de prière" {{ old('type', $service->type) == 'Culte de prière' ? 'selected' : '' }}>Culte de prière</option>
                    <option value="Culte spécial" {{ old('type', $service->type) == 'Culte spécial' ? 'selected' : '' }}>Culte spécial</option>
                    <option value="Culte de jeûne" {{ old('type', $service->type) == 'Culte de jeûne' ? 'selected' : '' }}>Culte de jeûne</option>
                    <option value="Culte de réveil" {{ old('type', $service->type) == 'Culte de réveil' ? 'selected' : '' }}>Culte de réveil</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Prédicateur</label>
                <input name="preacher" value="{{ old('preacher', $service->preacher) }}" class="form-control @error('preacher') is-invalid @enderror">
                @error('preacher')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Chorale</label>
                <input name="choir" value="{{ old('choir', $service->choir) }}" class="form-control @error('choir') is-invalid @enderror">
                @error('choir')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $service->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary">Enregistrer</button>
        </div>
    </form>
</div>
@endsection


