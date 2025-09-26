@extends('layouts.app')

@section('title', $documentFolder->name)

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

/* Icônes noires dans toute la section documents */
.documents-list .bi,
.documents-appbar .bi,
.document-details .bi,
.document-row-empty .bi,
.search-icon .bi,
.search-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.document-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}

/* Styles pour les cartes de statistiques */
.stats-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s ease;
}

.stats-card:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stats-label {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}

.stats-icon {
    font-size: 1.5rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Dossier -->
    <div class="appbar documents-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('document-folders.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">{{ $documentFolder->name }}</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('documents.create', ['folder_id' => $documentFolder->id]) }}" class="appbar-btn-yellow">
                    <i class="bi bi-file-plus"></i>
                    <span class="btn-text">Ajouter Document</span>
                </a>
                <a href="{{ route('document-folders.edit', $documentFolder) }}" class="appbar-btn-secondary">
                    <i class="bi bi-pencil"></i>
                    <span class="btn-text">Modifier</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques du dossier -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="d-flex flex-column align-items-center text-center">
                    <i class="bi bi-file-earmark-text stats-icon"></i>
                    <div class="stats-number">{{ $documents->total() }}</div>
                    <div class="stats-label">Total Documents</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="d-flex flex-column align-items-center text-center">
                    <i class="bi bi-image stats-icon"></i>
                    <div class="stats-number">{{ $documents->where('file_type', 'image')->count() }}</div>
                    <div class="stats-label">Images</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="d-flex flex-column align-items-center text-center">
                    <i class="bi bi-file-earmark-pdf stats-icon"></i>
                    <div class="stats-number">{{ $documents->where('file_type', 'pdf')->count() }}</div>
                    <div class="stats-label">PDFs</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stats-card">
                <div class="d-flex flex-column align-items-center text-center">
                    <i class="bi bi-hdd stats-icon"></i>
                    <div class="stats-number">{{ $documentFolder->formatted_size }}</div>
                    <div class="stats-label">Taille totale</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Champ de recherche -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par nom de document..." name="q" value="{{ request('q') }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
        </div>
    </form>

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
                            <i class="bi bi-file-earmark me-1"></i>{{ $document->formatted_size }}
                            <span class="ms-2"><i class="bi bi-tag me-1"></i>{{ strtoupper($document->file_extension) }}</span>
                            @if($document->description)
                                <span class="ms-2"><i class="bi bi-chat-text me-1"></i>{{ Str::limit($document->description, 40) }}</span>
                            @endif
                            @if($document->is_public)
                                <span class="ms-2"><i class="bi bi-globe me-1"></i>Public</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="document-actions">
                    <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-outline-success" onclick="event.stopPropagation()">Télécharger</a>
                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation()">Modifier</a>
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation()">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="document-row-empty">
                <i class="bi bi-folder-open"></i>
                <div>Dossier vide</div>
                <small class="text-muted mt-2">Ce dossier ne contient aucun document pour le moment</small>
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
