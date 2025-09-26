@extends('layouts.app')

@section('title', 'Gestion des Documents')

@section('content')
<style>
/* Styles pour la liste des documents */
.documents-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.document-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.document-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.document-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.document-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.document-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.document-date {
    margin-bottom: 4px;
}

.document-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.document-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.document-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.document-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.document-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
.search-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-icon {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 25px 0 0 25px;
    color: #000000;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-left: none;
    border-right: none;
    background-color: #ffffff;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 14px;
}

.search-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
    background-color: #ffffff;
}

.search-btn {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 25px 25px 0;
    color: #000000;
    font-weight: 600;
    padding: 12px 20px;
}

.search-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #000000;
}

.filter-select {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.filter-select:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

/* Icônes noires dans toute la section documents */
.documents-list .bi,
.documents-appbar .bi,
.document-details .bi,
.document-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.document-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}

/* Styles pour les statistiques */
.stats-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
}

.stats-card:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}

.stats-number {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.stats-label {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}
</style>
<div class="container-fluid py-4">
    <!-- AppBar Documents -->
    <div class="appbar documents-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Documents</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('documents.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouveau Document</span>
                </a>
            </div>
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
                    <select class="form-select filter-select" id="folderFilter" onchange="filterByFolder()">
                        <option value="">Tous les dossiers</option>
                        @foreach($folders as $folder)
                            <option value="{{ $folder->id }}" {{ request('folder_id') == $folder->id ? 'selected' : '' }}>
                                {{ $folder->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select filter-select" id="typeFilter" onchange="filterByType()">
                        <option value="">Tous les types</option>
                        <option value="images" {{ request('type') == 'images' ? 'selected' : '' }}>Images</option>
                        <option value="pdfs" {{ request('type') == 'pdfs' ? 'selected' : '' }}>PDFs</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <a href="{{ route('document-folders.index') }}" class="btn btn-outline-primary filter-btn">
                    <i class="bi bi-folder me-2"></i>Gérer les Dossiers
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card" data-filter="folders" style="cursor: pointer;">
                <div class="d-flex flex-column align-items-center text-center">
                    <h3 class="stats-number mb-2">{{ $folders->count() }}</h3>
                    <p class="stats-label mt-2">Dossiers</p>
                    <i class="bi bi-folder mt-2" style="font-size: 1.5rem; color: #64748b;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" data-filter="all" style="cursor: pointer;">
                <div class="d-flex flex-column align-items-center text-center">
                    <h3 class="stats-number mb-2">{{ $documents->total() }}</h3>
                    <p class="stats-label mt-2">Total Documents</p>
                    <i class="bi bi-file-earmark-text mt-2" style="font-size: 1.5rem; color: #64748b;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" data-filter="images" style="cursor: pointer;">
                <div class="d-flex flex-column align-items-center text-center">
                    <h3 class="stats-number mb-2">{{ $documents->where('file_type', 'image')->count() }}</h3>
                    <p class="stats-label mt-2">Images</p>
                    <i class="bi bi-image mt-2" style="font-size: 1.5rem; color: #64748b;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card" data-filter="pdfs" style="cursor: pointer;">
                <div class="d-flex flex-column align-items-center text-center">
                    <h3 class="stats-number mb-2">{{ $documents->where('file_type', 'pdf')->count() }}</h3>
                    <p class="stats-label mt-2">PDFs</p>
                    <i class="bi bi-file-earmark-pdf mt-2" style="font-size: 1.5rem; color: #64748b;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Message d'information -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info border-0" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                        <i class="mdi mdi-information text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">Gestion des documents</h6>
                        <p class="mb-0">Cliquez sur les cartes ci-dessus pour filtrer les documents par type, ou utilisez les filtres pour une recherche plus précise.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    <div class="documents-list">
        @forelse($documents as $index => $document)
            <div class="document-row {{ $index > 0 ? 'document-row-separated' : '' }}" onclick="window.location.href='{{ route('documents.show', $document) }}'" style="cursor: pointer;">
                <div class="document-row-body">
                    <div class="document-info">
                        <div class="document-date">
                            <span class="badge bg-custom">{{ $document->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="document-name">
                            {{ $document->name }}
                        </div>
                        <div class="document-details">
                            <i class="bi bi-folder me-1"></i>{{ $document->folder->name ?? 'Sans dossier' }}
                            <span class="ms-2"><i class="bi bi-file-earmark me-1"></i>{{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : '—' }}</span>
                            @if($document->file_type === 'image')
                                <span class="ms-2"><i class="bi bi-image me-1"></i>Image</span>
                            @elseif($document->file_type === 'pdf')
                                <span class="ms-2"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</span>
                            @else
                                <span class="ms-2"><i class="bi bi-file-earmark-text me-1"></i>Document</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="document-actions">
                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation()">Modifier</a>
                    <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); showDeleteConfirmation({{ $document->id }}, '{{ $document->name }}')">Supprimer</button>
                </div>
            </div>
        @empty
            <div class="document-row-empty">
                <i class="bi bi-file-earmark-text"></i>
                <div>Aucun document trouvé</div>
                <small class="text-muted mt-2">Commencez par ajouter votre premier document</small>
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

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-wrapper me-3">
                        <div class="modal-icon bg-danger-subtle text-danger">
                            <i class="mdi mdi-alert-circle"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="deleteConfirmationModalLabel">
                            Confirmer la suppression
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Cette action est irréversible</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="alert alert-warning border-0 mb-3" style="background-color: #fff3cd; border-radius: 12px;">
                    <div class="d-flex align-items-start">
                        <i class="mdi mdi-information text-warning me-2 mt-1"></i>
                        <div>
                            <strong>Attention !</strong>
                            <p class="mb-0 small">Vous êtes sur le point de supprimer définitivement le document :</p>
                        </div>
                    </div>
                </div>
                <div class="document-preview bg-light p-3 rounded" style="border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <div class="document-icon-small me-3">
                            <i class="mdi mdi-file-document-outline text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold" id="documentNamePreview"></h6>
                            <small class="text-muted">Ce document sera supprimé de manière permanente</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <div class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-light flex-fill" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-2"></i>Annuler
                    </button>
                    <form id="deleteDocumentForm" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger flex-fill">
                            <i class="mdi mdi-delete me-2"></i>Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour le modal de confirmation */
.modal-icon-wrapper {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.document-icon-small {
    width: 40px;
    height: 40px;
    background-color: #e3f2fd;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.modal-content {
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.modal-header {
    padding: 1.5rem 1.5rem 0 1.5rem;
}

.modal-body {
    padding: 0 1.5rem 1rem 1.5rem;
}

.modal-footer {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    opacity: 0.6;
}

.btn-close:hover {
    opacity: 1;
}

/* Animation pour le modal */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
    transform: translate(0, -50px);
}

.modal.show .modal-dialog {
    transform: none;
}

/* Styles pour les boutons du modal */
.modal-footer .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.modal-footer .btn:hover {
    transform: translateY(-1px);
}

.modal-footer .btn-danger {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
}

.modal-footer .btn-danger:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.modal-footer .btn-light {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
}

.modal-footer .btn-light:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #495057;
}
</style>

<script>
// Fonction pour afficher le modal de confirmation de suppression
function showDeleteConfirmation(documentId, documentName) {
    // Mettre à jour le nom du document dans le modal
    document.getElementById('documentNamePreview').textContent = documentName;
    
    // Mettre à jour l'action du formulaire
    const form = document.getElementById('deleteDocumentForm');
    form.action = `/documents/${documentId}`;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

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

// Fonction pour rendre les cartes de statistiques cliquables
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter l'événement de clic sur les cartes de statistiques
    const statsCards = document.querySelectorAll('.stats-clickable');
    
    statsCards.forEach(card => {
        card.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Rediriger vers les pages dédiées
            if (filter === 'folders') {
                // Rediriger vers la gestion des dossiers
                window.location.href = '{{ route("document-folders.index") }}';
            } else if (filter === 'all') {
                // Rediriger vers la page de tous les documents
                window.location.href = '{{ route("documents.all") }}';
            } else if (filter === 'images') {
                // Rediriger vers la page des images
                window.location.href = '{{ route("documents.images") }}';
            } else if (filter === 'pdfs') {
                // Rediriger vers la page des PDFs
                window.location.href = '{{ route("documents.pdfs") }}';
            }
        });
        
        // Ajouter un effet hover
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 10px -5px rgba(0, 0, 0, 0.04)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';
        });
    });
});
</script>
@endsection
