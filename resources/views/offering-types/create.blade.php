@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Nouveau type d'offrande</h1>
        <a href="{{ route('offering-types.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
    <form method="POST" action="{{ route('offering-types.store') }}" class="card p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input name="slug" value="{{ old('slug') }}" class="form-control @error('slug') is-invalid @enderror" placeholder="ex: ecole_du_sabbat">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Couleur (CSS)</label>
                <input name="color" value="{{ old('color') }}" class="form-control @error('color') is-invalid @enderror" placeholder="#FF2600 ou rgb(…)">
                @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Actif</label>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn">Enregistrer</button>
        </div>
    </form>
</div>
@endsection


