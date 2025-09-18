@extends('layouts.app')
@section('content')

<style>
/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6B7280;
    font-size: 1rem;
    margin-bottom: 0;
}

/* Form Card */
.form-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.form-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Form Groups */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #D1D5DB;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: #8B5CF6;
    box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #EF4444;
}

.form-control.is-invalid:focus {
    border-color: #EF4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback {
    display: block;
    color: #EF4444;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Image Repeater */
.image-repeater {
    margin-bottom: 1rem;
}

.input-group {
    display: flex;
    margin-bottom: 0.75rem;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #D1D5DB;
}

.input-group-text {
    background: #F9FAFB;
    border: none;
    padding: 0.75rem;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
}

.input-group .form-control {
    border: none;
    border-radius: 0;
    flex: 1;
}

.input-group .btn {
    border: none;
    border-radius: 0;
    padding: 0.75rem;
    background: #FEF2F2;
    color: #EF4444;
    transition: all 0.2s ease;
}

.input-group .btn:hover {
    background: #FEE2E2;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #E5E7EB;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn.primary {
    background: #8B5CF6;
    color: white;
    border-color: #8B5CF6;
}

.action-btn.primary:hover {
    background: #7C3AED;
    border-color: #7C3AED;
    color: white;
}

/* Add Image Button */
.add-image-btn {
    padding: 0.5rem 1rem;
    border: 1px dashed #D1D5DB;
    background: #F9FAFB;
    border-radius: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.add-image-btn:hover {
    border-color: #8B5CF6;
    background: #F3F4F6;
    color: #8B5CF6;
    text-decoration: none;
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Modifier l'événement</h1>
        <p class="page-subtitle">Modifiez les informations de l'événement</p>
    </div>

    <form method="POST" action="{{ route('events.update', $event) }}" class="form-card">
        @csrf
        @method('PUT')
        
        <div class="row g-3">
            <!-- Titre -->
            <div class="col-md-8">
                <div class="form-group">
                    <label class="form-label">Titre de l'événement</label>
                    <input name="title" value="{{ old('title', $event->title) }}" class="form-control @error('title') is-invalid @enderror" required placeholder="Ex: Conférence de Noël">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Date -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" value="{{ old('date', optional($event->date)->format('Y-m-d')) }}" class="form-control @error('date') is-invalid @enderror" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Type -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Type d'événement</label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="Conférence" {{ old('type', $event->type) == 'Conférence' ? 'selected' : '' }}>Conférence</option>
                        <option value="Célébration" {{ old('type', $event->type) == 'Célébration' ? 'selected' : '' }}>Célébration</option>
                        <option value="Réunion" {{ old('type', $event->type) == 'Réunion' ? 'selected' : '' }}>Réunion</option>
                        <option value="Formation" {{ old('type', $event->type) == 'Formation' ? 'selected' : '' }}>Formation</option>
                        <option value="Autre" {{ old('type', $event->type) == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Lieu -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Lieu</label>
                    <input name="location" value="{{ old('location', $event->location) }}" class="form-control @error('location') is-invalid @enderror" placeholder="Ex: Salle principale">
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Heure début -->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Heure début</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $event->start_time) }}" class="form-control @error('start_time') is-invalid @enderror">
                    @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Heure fin -->
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Heure fin</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $event->end_time) }}" class="form-control @error('end_time') is-invalid @enderror">
                    @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Description -->
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Décrivez l'événement...">{{ old('description', $event->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Images -->
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">Images (URLs Firebase)</label>
                    <div id="images-repeater" class="image-repeater">
                        @php $oldImages = old('images', $event->images ?? ['']); @endphp
                        @foreach($oldImages as $i => $url)
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input name="images[]" value="{{ $url }}" placeholder="https://firebasestorage.googleapis.com/..." class="form-control @error('images.'.$i) is-invalid @enderror">
                            <button class="btn" type="button" onclick="this.closest('.input-group').remove()">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @error('images.'.$i)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        @endforeach
                    </div>
                    <button class="add-image-btn" type="button" onclick="addImageField()">
                        <i class="bi bi-plus-lg"></i> 
                        Ajouter une URL d'image
                    </button>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('events.index') }}" class="action-btn">
                <i class="bi bi-arrow-left"></i>
                Annuler
            </a>
            <button type="submit" class="action-btn primary">
                <i class="bi bi-check"></i>
                Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
function addImageField() {
    const wrap = document.getElementById('images-repeater');
    const div = document.createElement('div');
    div.className = 'input-group';
    div.innerHTML = '<span class="input-group-text"><i class="bi bi-image"></i></span>' +
        '<input name="images[]" placeholder="https://firebasestorage.googleapis.com/..." class="form-control">' +
        '<button class="btn" type="button" onclick="this.closest(\'.input-group\').remove()">' +
        '<i class="bi bi-x"></i></button>';
    wrap.appendChild(div);
}
</script>
@endsection