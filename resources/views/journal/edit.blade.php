@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Modifier l'entrée</h1>
        <a href="{{ route('journal.show', $entry) }}" class="btn btn-outline-secondary">Retour</a>
    </div>

    <form action="{{ route('journal.update', $entry) }}" method="POST" enctype="multipart/form-data" class="card card-soft p-3">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12 col-md-8">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title', $entry->title) }}" />
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Catégorie</label>
                <select name="category" class="form-select" required>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" @selected(old('category', $entry->category)===$key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="occurred_at" class="form-control" required value="{{ old('occurred_at', optional($entry->occurred_at)->format('Y-m-d')) }}" />
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="6" class="form-control">{{ old('description', $entry->description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Ajouter des images</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple />
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Mettre à jour</button>
            <a class="btn btn-outline-secondary" href="{{ route('journal.show', $entry) }}">Annuler</a>
        </div>
    </form>
</div>
@endsection


