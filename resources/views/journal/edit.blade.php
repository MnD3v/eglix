@extends('layouts.app')

@section('content')
<style>
/* Styles pour les champs de formulaire arrondis */
.form-control, .form-select, .form-label {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les sections du formulaire */
.form-section {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* Styles pour les boutons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Styles pour l'upload d'images */
.image-upload-container {
    margin-top: 1rem;
}

.image-upload-area {
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.image-upload-area:hover {
    border-color: #FFCC00;
    background: #fffef7;
}

.upload-button {
    background: #FFCC00;
    color: #000000;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all 0.3s ease;
}

.upload-button:hover {
    background: #e6b800;
    transform: translateY(-1px);
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Entrée Journal -->
    <div class="appbar journal-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('journal.show', $entry) }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier l'Entrée</h1>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('journal.update', $entry) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <!-- Section Informations de l'Entrée -->
        <div class="form-section">
            <h2 class="section-title">Informations de l'Entrée</h2>
            <p class="section-subtitle">Détails sur l'entrée du journal à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required value="{{ old('title', $entry->title) }}" placeholder="Ex: Célébration de Pâques 2024" />
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Catégorie</label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" @selected(old('category', $entry->category)===$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="occurred_at" class="form-control @error('occurred_at') is-invalid @enderror" required value="{{ old('occurred_at', optional($entry->occurred_at)->format('Y-m-d')) }}" />
                    @error('occurred_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="form-section">
            <h2 class="section-title">Description</h2>
            <p class="section-subtitle">Description détaillée de l'événement</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Décrivez l'événement en détail...">{{ old('description', $entry->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Images -->
        <div class="form-section">
            <h2 class="section-title">Images</h2>
            <p class="section-subtitle">Ajouter des images à l'entrée du journal</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <div class="image-upload-container">
                        <div class="image-upload-area" id="imageUploadArea">
                            <div class="upload-content">
                                <button type="button" class="upload-button" id="uploadButton">
                                    <i class="bi bi-camera me-2"></i>Ajouter des Images
                                </button>
                                <div class="upload-text mt-2">
                                    <p class="text-muted mb-0">Ou déposez des images ici</p>
                                    <small class="text-muted">JPG, PNG, WEBP • Max 4MB par image</small>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="images[]" id="imageInput" accept="image/*" multiple class="d-none @error('images') is-invalid @enderror">
                        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('journal.show', $entry) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    
    // Gestion du clic sur la zone d'upload
    uploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    // Gestion du drag & drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#FFCC00';
        uploadArea.style.background = '#fffef7';
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
        }
    });
    
    // Gestion de la sélection de fichiers
    imageInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            console.log(`${e.target.files.length} image(s) sélectionnée(s)`);
        }
    });
});
</script>
@endsection


