@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Ajouter une fonction administrative</h1>
        @include('partials.back-button')
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-soft">
                <div class="card-body">
                    <form action="{{ route('administration.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="member_id" class="form-label">Membre <span class="text-danger">*</span></label>
                                <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un membre</option>
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

                            <div class="col-12">
                                <label for="function_name" class="form-label">Fonction <span class="text-danger">*</span></label>
                                @if(count($functions) > 0)
                                    <select name="function_name" id="function_name" class="form-select @error('function_name') is-invalid @enderror" required>
                                        <option value="">Sélectionner une fonction</option>
                                        @foreach($functions as $key => $value)
                                            <option value="{{ $key }}" {{ old('function_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Aucun type de fonction n'a été créé. 
                                        <a href="{{ route('administration-function-types.create') }}" class="alert-link">Créer des types de fonctions</a> d'abord.
                                    </div>
                                    <input type="text" name="function_name" id="function_name" class="form-control @error('function_name') is-invalid @enderror" 
                                           value="{{ old('function_name') }}" placeholder="Nom de la fonction" required>
                                @endif
                                @error('function_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Date de fin</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date') }}">
                                <div class="form-text">Laisser vide si la fonction est toujours en cours</div>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Fonction active
                                    </label>
                                </div>
                                <div class="form-text">Décochez si la fonction est suspendue</div>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" rows="3" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          placeholder="Notes supplémentaires sur cette fonction...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('administration.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
