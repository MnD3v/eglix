@extends('layouts.app')

@section('title', $documentFolder->name)

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
                        <li class="breadcrumb-item active">{{ $documentFolder->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3" style="background-color: {{ $documentFolder->color }}20;">
                            <span class="avatar-title rounded" style="background-color: {{ $documentFolder->color }}; color: white;">
                                <i class="mdi mdi-folder-outline font-size-18"></i>
                            </span>
                        </div>
                        <div>
                            <span>{{ $documentFolder->name }}</span>
                            @if($documentFolder->description)
                                <small class="text-muted d-block">{{ $documentFolder->description }}</small>
                            @endif
                        </div>
                    </div>
                </h4>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('documents.create', ['folder_id' => $documentFolder->id]) }}" class="btn btn-primary">
                <i class="mdi mdi-file-plus"></i> Ajouter un Document
            </a>
            <a href="{{ route('document-folders.edit', $documentFolder) }}" class="btn btn-outline-warning">
                <i class="mdi mdi-pencil"></i> Modifier le Dossier
            </a>
            <a href="{{ route('document-folders.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Retour aux Dossiers
            </a>
        </div>
    </div>

    <!-- Statistiques du dossier -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-primary-subtle">
                                <span class="avatar-title bg-primary rounded">
                                    <i class="mdi mdi-file-document-outline"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->total() }}</h5>
                            <p class="text-muted mb-0">Total Documents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-success-subtle">
                                <span class="avatar-title bg-success rounded">
                                    <i class="mdi mdi-image"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->where('file_type', 'image')->count() }}</h5>
                            <p class="text-muted mb-0">Images</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-danger-subtle">
                                <span class="avatar-title bg-danger rounded">
                                    <i class="mdi mdi-file-pdf-box"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->where('file_type', 'pdf')->count() }}</h5>
                            <p class="text-muted mb-0">PDFs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded bg-info-subtle">
                                <span class="avatar-title bg-info rounded">
                                    <i class="mdi mdi-weight"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documentFolder->formatted_size }}</h5>
                            <p class="text-muted mb-0">Taille totale</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    <div class="row">
        @forelse($documents as $document)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <div class="card document-card h-100 shadow-sm border-0" style="transition: all 0.3s ease; border-radius: 16px;">
                    <div class="card-body p-4">
                        <!-- Header avec icône et type -->
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="document-icon-wrapper">
                                @if($document->is_image)
                                    <div class="document-image-container">
                                        <img src="{{ $document->thumbnail_url }}" alt="{{ $document->name }}" 
                                             class="document-image">
                                    </div>
                                @else
                                    <div class="document-icon bg-{{ $document->file_color }}-subtle text-{{ $document->file_color }}">
                                        <i class="mdi {{ $document->file_icon }}"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="document-badges">
                                <span class="badge bg-{{ $document->file_color }}-subtle text-{{ $document->file_color }} document-type-badge">
                                    {{ strtoupper($document->file_extension) }}
                                </span>
                                @if($document->is_public)
                                    <span class="badge bg-success-subtle text-success document-public-badge">
                                        <i class="mdi mdi-earth"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Contenu principal -->
                        <div class="document-content">
                            <h6 class="document-title mb-2">{{ $document->name }}</h6>
                            
                            @if($document->description)
                                <p class="document-description text-muted small mb-3">{{ Str::limit($document->description, 60) }}</p>
                            @endif
                            
                            <!-- Métadonnées -->
                            <div class="document-metadata">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="mdi mdi-file-outline me-2"></i>
                                        <span class="document-size">{{ $document->formatted_size }}</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="mdi mdi-calendar-outline me-1"></i>
                                        {{ $document->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="document-actions mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('documents.show', $document) }}" 
                                       class="btn btn-sm btn-outline-primary document-action-btn" 
                                       title="Voir">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="{{ route('documents.download', $document) }}" 
                                       class="btn btn-sm btn-outline-success document-action-btn" 
                                       title="Télécharger">
                                        <i class="mdi mdi-download"></i>
                                    </a>
                                    <a href="{{ route('documents.edit', $document) }}" 
                                       class="btn btn-sm btn-outline-warning document-action-btn" 
                                       title="Modifier">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                </div>
                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger document-action-btn" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-light text-muted rounded">
                            <i class="mdi mdi-folder-open-outline font-size-24"></i>
                        </div>
                    </div>
                    <h4>Dossier vide</h4>
                    <p class="text-muted">Ce dossier ne contient aucun document pour le moment.</p>
                    <a href="{{ route('documents.create', ['folder_id' => $documentFolder->id]) }}" class="btn btn-primary">
                        <i class="mdi mdi-file-plus"></i> Ajouter le Premier Document
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $documents->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
