@extends('layouts.app')

@section('title', 'Créer un Dossier')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('document-folders.index') }}">Dossiers</a></li>
                        <li class="breadcrumb-item active">Créer</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-folder-plus me-1"></i>
                    Créer un Dossier
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document-folders.store') }}" method="POST" id="folderForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du dossier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Couleur</label>
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', '#3B82F6') }}">
                                    @error('color')
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Dossier actif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu du dossier -->
                        <div class="mb-3">
                            <label class="form-label">Aperçu</label>
                            <div class="card border" id="folderPreview">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded" id="previewIcon" style="background-color: #3B82F620;">
                                                <span class="avatar-title rounded" id="previewColor" style="background-color: #3B82F6; color: white;">
                                                    <i class="mdi mdi-folder-outline font-size-18"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-1" id="previewName">Nom du dossier</h5>
                                            <p class="text-muted small mb-2" id="previewDescription">Description du dossier</p>
                                            <span class="badge bg-success-subtle text-success" id="previewStatus">Actif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('document-folders.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Créer le Dossier
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
                        <h6 class="alert-heading">Conseils :</h6>
                        <ul class="mb-0">
                            <li>Choisissez un nom descriptif</li>
                            <li>Utilisez des couleurs pour organiser visuellement</li>
                            <li>L'ordre d'affichage permet de trier les dossiers</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Exemples de dossiers :</h6>
                        <ul class="mb-0">
                            <li>Bulletins paroissiaux</li>
                            <li>Photos d'événements</li>
                            <li>Documents administratifs</li>
                            <li>Prédications</li>
                            <li>Rapports financiers</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const isActiveInput = document.getElementById('is_active');
    
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const previewColor = document.getElementById('previewColor');
    const previewIcon = document.getElementById('previewIcon');
    const previewStatus = document.getElementById('previewStatus');

    function updatePreview() {
        const name = nameInput.value || 'Nom du dossier';
        const description = descriptionInput.value || 'Description du dossier';
        const color = colorInput.value;
        const isActive = isActiveInput.checked;

        previewName.textContent = name;
        previewDescription.textContent = description;
        previewColor.style.backgroundColor = color;
        previewIcon.style.backgroundColor = color + '20';
        
        if (isActive) {
            previewStatus.textContent = 'Actif';
            previewStatus.className = 'badge bg-success-subtle text-success';
        } else {
            previewStatus.textContent = 'Inactif';
            previewStatus.className = 'badge bg-danger-subtle text-danger';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updatePreview);
    isActiveInput.addEventListener('change', updatePreview);

    // Initialiser l'aperçu
    updatePreview();
});
</script>
@endsection
