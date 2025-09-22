@extends('layouts.app')

@section('title', $document->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('document-folders.show', $document->folder) }}">{{ $document->folder->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $document->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <div class="d-flex align-items-center">
                        @if($document->is_image)
                            <img src="{{ $document->thumbnail_url }}" alt="{{ $document->name }}" 
                                 class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-{{ $document->is_pdf ? 'danger' : 'secondary' }}-subtle text-{{ $document->is_pdf ? 'danger' : 'secondary' }} rounded">
                                    <i class="mdi mdi-{{ $document->is_pdf ? 'file-pdf-box' : 'file-document-outline' }} font-size-18"></i>
                                </div>
                            </div>
                        @endif
                        <div>
                            <span>{{ $document->name }}</span>
                            <small class="text-muted d-block">{{ $document->folder->name }}</small>
                        </div>
                    </div>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @if($document->is_image)
                        <div class="text-center mb-4">
                            <img src="{{ $document->file_url }}" alt="{{ $document->name }}" 
                                 class="img-fluid rounded" style="max-height: 500px;">
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-{{ $document->is_pdf ? 'danger' : 'secondary' }}-subtle text-{{ $document->is_pdf ? 'danger' : 'secondary' }} rounded">
                                    <i class="mdi mdi-{{ $document->is_pdf ? 'file-pdf-box' : 'file-document-outline' }} font-size-48"></i>
                                </div>
                            </div>
                            <h4>{{ $document->name }}</h4>
                            <p class="text-muted">{{ $document->formatted_size }}</p>
                        </div>
                    @endif

                    @if($document->description)
                        <div class="mt-4">
                            <h6>Description</h6>
                            <p class="text-muted">{{ $document->description }}</p>
                        </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">
                            <i class="mdi mdi-download"></i> Télécharger
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-outline-warning">
                                <i class="mdi mdi-pencil"></i> Modifier
                            </a>
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                    <i class="mdi mdi-delete"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
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
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="mb-1">{{ $document->formatted_size }}</h6>
                                <p class="text-muted mb-0 small">Taille</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="mb-1">{{ strtoupper($document->file_extension) }}</h6>
                                <p class="text-muted mb-0 small">Format</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Détails du fichier</h6>
                        <ul class="list-unstyled">
                            <li><strong>Nom original :</strong> {{ $document->original_name }}</li>
                            <li><strong>Type MIME :</strong> {{ $document->mime_type }}</li>
                            <li><strong>Dossier :</strong> 
                                <a href="{{ route('document-folders.show', $document->folder) }}" class="text-decoration-none">
                                    {{ $document->folder->name }}
                                </a>
                            </li>
                            <li><strong>Statut :</strong> 
                                <span class="badge bg-{{ $document->is_public ? 'success' : 'warning' }}-subtle text-{{ $document->is_public ? 'success' : 'warning' }}">
                                    {{ $document->is_public ? 'Public' : 'Privé' }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6>Dates</h6>
                        <ul class="list-unstyled">
                            <li><strong>Créé le :</strong> {{ $document->created_at->format('d/m/Y à H:i') }}</li>
                            <li><strong>Modifié le :</strong> {{ $document->updated_at->format('d/m/Y à H:i') }}</li>
                            @if($document->creator)
                                <li><strong>Créé par :</strong> {{ $document->creator->name }}</li>
                            @endif
                        </ul>
                    </div>

                    @if($document->is_image)
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Image</h6>
                            <p class="mb-0">Cliquez sur l'image pour la voir en taille réelle dans un nouvel onglet.</p>
                        </div>
                    @elseif($document->is_pdf)
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">Document PDF</h6>
                            <p class="mb-0">Utilisez le bouton "Télécharger" pour ouvrir le PDF dans votre navigateur.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($document->is_image)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const img = document.querySelector('img[src="{{ $document->file_url }}"]');
    if (img) {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            window.open('{{ $document->file_url }}', '_blank');
        });
    }
});
</script>
@endif
@endsection
