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

/* Styles pour l'upload de fichier */
.file-upload-container {
    margin-top: 1rem;
}

.file-upload-area {
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
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
    <!-- AppBar Modifier Document -->
    <div class="appbar documents-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('document-folders.show', $document->folder) }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier le Document</h1>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Section Informations du Document -->
        <div class="form-section">
            <h2 class="section-title">Informations du Document</h2>
            <p class="section-subtitle">Détails sur le document à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom du Document</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name', $document->name) }}" required placeholder="Ex: Rapport annuel 2024">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dossier</label>
                    <select class="form-select @error('folder_id') is-invalid @enderror" 
                            name="folder_id" required>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}" 
                                    {{ old('folder_id', $document->folder_id) == $folder->id ? 'selected' : '' }}>
                                {{ $folder->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('folder_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="form-section">
            <h2 class="section-title">Description</h2>
            <p class="section-subtitle">Description du document</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              name="description" rows="4" placeholder="Description du document...">{{ old('description', $document->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Paramètres -->
        <div class="form-section">
            <h2 class="section-title">Paramètres</h2>
            <p class="section-subtitle">Configuration du document</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" 
                               {{ old('is_public', $document->is_public) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_public">
                            Document public (accessible sans authentification)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Fichier Actuel -->
        <div class="form-section">
            <h2 class="section-title">Fichier Actuel</h2>
            <p class="section-subtitle">Informations sur le fichier actuel</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="current-file-card">
                        <div class="d-flex align-items-center p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                            @if($document->is_image)
                                <img src="{{ $document->thumbnail_url }}" alt="{{ $document->name }}" 
                                     class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="file-icon me-3" style="width: 60px; height: 60px; background: #FFCC00; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-file-earmark" style="font-size: 1.5rem; color: #000000;"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $document->original_name }}</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $document->formatted_size }} • {{ strtoupper($document->file_extension) }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('document-folders.show', $document->folder) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Mettre à jour le Document
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
