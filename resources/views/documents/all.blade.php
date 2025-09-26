@extends('layouts.app')

@section('title', 'Tous les Documents')

@section('content')
<div class="container-fluid py-4">
    <!-- Header de section moderne -->
    <div class="section-header">
        <h1 class="section-title">
            <div class="section-title-icon">
                <i class="mdi mdi-file-document-outline"></i>
            </div>
            Tous les Documents
        </h1>
        <p class="section-subtitle">
            <i class="mdi mdi-folder-multiple section-subtitle-icon"></i>
            Tous vos documents, tous dossiers confondus
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('documents.index') }}" class="text-decoration-none">
                            <i class="mdi mdi-folder-multiple-outline me-1"></i>Documents
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="mdi mdi-file-document-outline me-1"></i>Tous les Documents
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

    <!-- Grille des documents -->
    <div class="row">
        @forelse($documents as $document)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                <div class="card document-card h-100 shadow-sm border-0 document-clickable" 
                     style="transition: all 0.3s ease; border-radius: 16px; cursor: pointer;" 
                     data-href="{{ route('documents.show', $document) }}">
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
                            <h6 class="document-title text-dark mb-2" title="{{ $document->name }}">
                                {{ Str::limit($document->name, 30) }}
                            </h6>
                            
                            <div class="document-meta mb-3">
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="mdi mdi-file-outline me-1"></i>
                                    <span>{{ $document->formatted_size }}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small mb-1">
                                    <i class="mdi mdi-folder-outline me-1"></i>
                                    <span>{{ $document->folder->name ?? 'Sans dossier' }}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="mdi mdi-calendar-outline me-1"></i>
                                    <span>{{ $document->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            @if($document->description)
                                <p class="document-description text-muted small mb-3">
                                    {{ Str::limit($document->description, 80) }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="document-actions d-flex justify-content-between align-items-center">
                            <div class="document-status">
                                @if($document->is_public)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="mdi mdi-earth me-1"></i>Public
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="mdi mdi-lock me-1"></i>Privé
                                    </span>
                                @endif
                            </div>
                            
                            <div class="document-menu">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown" 
                                            aria-expanded="false" onclick="event.stopPropagation();">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('documents.show', $document) }}">
                                                <i class="mdi mdi-eye me-2"></i>Voir
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('documents.download', $document) }}">
                                                <i class="mdi mdi-download me-2"></i>Télécharger
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('documents.edit', $document) }}">
                                                <i class="mdi mdi-pencil me-2"></i>Modifier
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.stopPropagation(); showDeleteConfirmation({{ $document->id }}, '{{ $document->name }}');">
                                                <i class="mdi mdi-delete me-2"></i>Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-state-icon mb-4">
                            <i class="mdi mdi-folder-open-outline text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted mb-3">Aucun document trouvé</h4>
                        <p class="text-muted mb-4">
                            Commencez par ajouter votre premier document à votre bibliothèque.
                        </p>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-2"></i>Ajouter un document
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($documents->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Fonction pour rendre les cartes cliquables
document.addEventListener('DOMContentLoaded', function() {
    const documentCards = document.querySelectorAll('.document-clickable');
    
    documentCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.dropdown')) {
                return;
            }
            
            const href = this.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        });
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';
        });
    });
});

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

