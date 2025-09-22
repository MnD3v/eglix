@extends('layouts.app')

@section('title', 'Modifier le Dossier')

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
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-folder-edit me-1"></i>
                    Modifier le Dossier
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document-folders.update', $documentFolder) }}" method="POST" id="folderForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du dossier <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $documentFolder->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Couleur</label>
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', $documentFolder->color) }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $documentFolder->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $documentFolder->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $documentFolder->is_active) ? 'checked' : '' }}>
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
                                            <div class="avatar-sm rounded" id="previewIcon" style="background-color: {{ $documentFolder->color }}20;">
                                                <span class="avatar-title rounded" id="previewColor" style="background-color: {{ $documentFolder->color }}; color: white;">
                                                    <i class="mdi mdi-folder-outline font-size-18"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-1" id="previewName">{{ $documentFolder->name }}</h5>
                                            <p class="text-muted small mb-2" id="previewDescription">{{ $documentFolder->description ?: 'Description du dossier' }}</p>
                                            <span class="badge bg-success-subtle text-success" id="previewStatus">{{ $documentFolder->is_active ? 'Actif' : 'Inactif' }}</span>
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
                                <i class="mdi mdi-content-save"></i> Mettre à jour le Dossier
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
                        Informations du dossier
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="mb-1">{{ $documentFolder->documents_count }}</h4>
                                <p class="text-muted mb-0 small">Documents</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="mb-1">{{ $documentFolder->formatted_size }}</h4>
                                <p class="text-muted mb-0 small">Taille totale</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Statistiques :</h6>
                        <ul class="mb-0">
                            <li>Créé le : {{ $documentFolder->created_at->format('d/m/Y') }}</li>
                            <li>Modifié le : {{ $documentFolder->updated_at->format('d/m/Y') }}</li>
                            <li>Créé par : {{ $documentFolder->creator->name ?? 'Utilisateur' }}</li>
                        </ul>
                    </div>

                    @if($documentFolder->documents_count > 0)
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Attention :</h6>
                            <p class="mb-0">Ce dossier contient {{ $documentFolder->documents_count }} document(s). 
                            La modification du nom ou de la couleur n'affectera pas les documents existants.</p>
                        </div>
                    @endif
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
