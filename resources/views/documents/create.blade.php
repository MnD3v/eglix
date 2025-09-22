@extends('layouts.app')

@section('title', 'Ajouter un Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-file-plus me-1"></i>
                    Ajouter un Document
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="documentForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du document <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="folder_id" class="form-label">Dossier <span class="text-danger">*</span></label>
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

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept="image/*,.pdf" required>
                            <div class="form-text">
                                Formats acceptés : Images (JPEG, PNG, GIF, WebP) et PDF. Taille maximale : 10MB
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" 
                                       {{ old('is_public') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Document public (accessible sans authentification)
                                </label>
                            </div>
                        </div>

                        <!-- Aperçu du fichier -->
                        <div id="filePreview" class="mb-3" style="display: none;">
                            <label class="form-label">Aperçu</label>
                            <div class="text-center">
                                <img id="previewImage" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 200px;">
                                <div id="previewPdf" class="d-none">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded">
                                            <i class="mdi mdi-file-pdf-box font-size-24"></i>
                                        </div>
                                    </div>
                                    <p class="text-muted mt-2">Fichier PDF sélectionné</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="mdi mdi-upload"></i> Uploader le Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-information-outline"></i>
                        Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Formats supportés :</h6>
                        <ul class="mb-0">
                            <li><strong>Images :</strong> JPEG, PNG, GIF, WebP</li>
                            <li><strong>Documents :</strong> PDF</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Limitations :</h6>
                        <ul class="mb-0">
                            <li>Taille maximale : 10MB par fichier</li>
                            <li>Stockage sécurisé sur Firebase</li>
                            <li>Accès contrôlé par église</li>
                        </ul>
                    </div>

                    @if($folders->isEmpty())
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Aucun dossier disponible</h6>
                            <p class="mb-2">Vous devez d'abord créer un dossier pour organiser vos documents.</p>
                            <a href="{{ route('document-folders.create') }}" class="btn btn-sm btn-danger">
                                <i class="mdi mdi-folder-plus"></i> Créer un Dossier
                            </a>
                        </div>
                    @endif
                </div>
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
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Upload en cours...';
    });
});
</script>
@endsection
