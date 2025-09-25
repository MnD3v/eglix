@extends('layouts.app')

@section('title', 'Gestion des Documents')

@section('content')
<div class="container-fluid">
    <!-- Header de section moderne -->
    <div class="section-header">
        <h1 class="section-title">
            <div class="section-title-icon">
                <i class="mdi mdi-folder-multiple-outline"></i>
            </div>
            Documents
        </h1>
        <p class="section-subtitle">
            <i class="mdi mdi-shield-check section-subtitle-icon"></i>
            Organisez et gérez tous vos documents d'église en toute sécurité
        </p>
        
        <div class="section-actions">
            <a href="{{ route('documents.create') }}" class="section-action-btn">
                <i class="mdi mdi-plus"></i>
                Nouveau Document
            </a>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <i class="mdi mdi-home me-1"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="mdi mdi-folder-multiple-outline me-1"></i>Documents
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <select class="form-select" id="folderFilter" onchange="filterByFolder()">
                        <option value="">Tous les dossiers</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}" {{ request('folder_id') == $folder->id ? 'selected' : '' }}>
                                {{ $folder->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="typeFilter" onchange="filterByType()">
                        <option value="">Tous les types</option>
                        <option value="images" {{ request('type') == 'images' ? 'selected' : '' }}>Images</option>
                        <option value="pdfs" {{ request('type') == 'pdfs' ? 'selected' : '' }}>PDFs</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <a href="{{ route('document-folders.index') }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-folder-multiple me-2"></i>Gérer les Dossiers
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon stats-icon-primary">
                    <i class="mdi mdi-file-document-outline"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $documents->total() }}</h3>
                    <p class="stats-label">Total Documents</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon stats-icon-success">
                    <i class="mdi mdi-image"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $documents->where('file_type', 'image')->count() }}</h3>
                    <p class="stats-label">Images</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon stats-icon-danger">
                    <i class="mdi mdi-file-pdf-box"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $documents->where('file_type', 'pdf')->count() }}</h3>
                    <p class="stats-label">PDFs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="stats-icon stats-icon-info">
                    <i class="mdi mdi-folder-multiple"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $folders->count() }}</h3>
                    <p class="stats-label">Dossiers</p>
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
                                <div class="d-flex align-items-center text-muted small mb-2">
                                    <i class="mdi mdi-folder-outline me-2"></i>
                                    <span class="document-folder">{{ $document->folder->name }}</span>
                                </div>
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
                            <i class="mdi mdi-file-document-outline font-size-24"></i>
                        </div>
                    </div>
                    <h4>Aucun document trouvé</h4>
                    <p class="text-muted">Commencez par créer un dossier et uploader vos premiers documents.</p>
                    <a href="{{ route('document-folders.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-folder-plus"></i> Créer un Dossier
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $documents->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<script>
function filterByFolder() {
    const folderId = document.getElementById('folderFilter').value;
    const url = new URL(window.location);
    
    if (folderId) {
        url.searchParams.set('folder_id', folderId);
    } else {
        url.searchParams.delete('folder_id');
    }
    
    window.location.href = url.toString();
}

function filterByType() {
    const type = document.getElementById('typeFilter').value;
    const url = new URL(window.location);
    
    if (type) {
        url.searchParams.set('type', type);
    } else {
        url.searchParams.delete('type');
    }
    
    window.location.href = url.toString();
}
</script>
@endsection
