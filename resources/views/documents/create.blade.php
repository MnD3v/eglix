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

/* Styles pour les checkboxes */
.form-check-input {
    border-radius: 6px;
    border: 2px solid #e2e8f0;
}

.form-check-input:checked {
    background-color: #FFCC00;
    border-color: #FFCC00;
}

.form-check-label {
    font-weight: 500;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Styles pour l'aperçu de fichier */
.file-preview {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    background: #f8fafc;
    text-align: center;
}

.preview-image {
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.preview-pdf {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouveau Document -->
    <div class="appbar documents-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('documents.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouveau Document</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="documentForm">
                @csrf
                
                <!-- Section Informations du Document -->
                <div class="form-section">
                    <h2 class="section-title">Informations du Document</h2>
                    <p class="section-subtitle">Détails de base sur le document à ajouter</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom du Document</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: Rapport annuel 2024">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="folder_id" class="form-label">Dossier</label>
                            <select class="form-select @error('folder_id') is-invalid @enderror" 
                                    id="folder_id" name="folder_id" required>
                                <option value="">Sélectionner un dossier</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}" 
                                            {{ old('folder_id', $selectedFolder) == $folder->id ? 'selected' : '' }}>
                                        {{ $folder->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('folder_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="form-section">
                    <h2 class="section-title">Description</h2>
                    <p class="section-subtitle">Informations complémentaires sur ce document</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" placeholder="Décrivez le contenu et l'objectif de ce document...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Fichier -->
                <div class="form-section">
                    <h2 class="section-title">Fichier</h2>
                    <p class="section-subtitle">Sélectionnez le fichier à téléverser</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="file" class="form-label">Fichier</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept="image/*,.pdf" required>
                            <div class="form-text">
                                Formats acceptés : Images (JPEG, PNG, GIF, WebP) et PDF. Taille maximale : 10MB
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Paramètres -->
                <div class="form-section">
                    <h2 class="section-title">Paramètres</h2>
                    <p class="section-subtitle">Options de visibilité et d'accès</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" 
                                       {{ old('is_public') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    <i class="bi bi-globe me-2"></i>Document public (accessible sans authentification)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aperçu du fichier -->
                <div id="filePreview" class="form-section" style="display: none;">
                    <h2 class="section-title">Aperçu</h2>
                    <p class="section-subtitle">Prévisualisation du fichier sélectionné</p>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="file-preview">
                                <img id="previewImage" src="" alt="Aperçu" class="preview-image">
                                <div id="previewPdf" class="preview-pdf d-none">
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-pdf" style="font-size: 3rem; color: #dc3545;"></i>
                                        <p class="text-muted mt-2">Fichier PDF sélectionné</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="bi bi-upload me-2"></i>Téléverser le Document
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="form-section">
                <h2 class="section-title">Informations</h2>
                <p class="section-subtitle">Formats supportés et limitations</p>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="bi bi-file-earmark-text me-2"></i>Formats supportés :
                    </h6>
                    <ul class="mb-0">
                        <li><strong>Images :</strong> JPEG, PNG, GIF, WebP</li>
                        <li><strong>Documents :</strong> PDF</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>Limitations :
                    </h6>
                    <ul class="mb-0">
                        <li>Taille maximale : 10MB par fichier</li>
                        <li>Stockage sécurisé sur Firebase</li>
                        <li>Accès contrôlé par église</li>
                    </ul>
                </div>

                @if($folders->isEmpty())
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="bi bi-folder-x me-2"></i>Aucun dossier disponible
                        </h6>
                        <p class="mb-2">Vous devez d'abord créer un dossier pour organiser vos documents.</p>
                        <a href="{{ route('document-folders.create') }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-folder-plus me-2"></i>Créer un Dossier
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const previewDiv = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const previewPdf = document.getElementById('previewPdf');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('documentForm');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            previewDiv.style.display = 'block';
            
            if (file.type.startsWith('image/')) {
                previewImage.src = URL.createObjectURL(file);
                previewImage.style.display = 'block';
                previewPdf.classList.add('d-none');
            } else if (file.type === 'application/pdf') {
                previewImage.style.display = 'none';
                previewPdf.classList.remove('d-none');
            }
        } else {
            previewDiv.style.display = 'none';
        }
    });

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-upload me-2"></i>Upload en cours...';
    });
});
</script>
@endsection
