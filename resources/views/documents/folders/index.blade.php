@extends('layouts.app')

@section('title', 'Gestion des Dossiers')

@section('content')
<style>
/* Styles pour la liste des dossiers */
.folders-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.folder-row {
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

.folder-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.folder-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.folder-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.folder-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.folder-date {
    margin-bottom: 4px;
}

.folder-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.folder-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.folder-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.folder-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.folder-row-empty i {
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

/* Icônes noires dans toute la section dossiers */
.folders-list .bi,
.folders-appbar .bi,
.folder-details .bi,
.folder-row-empty .bi,
.search-icon .bi,
.search-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.folder-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Dossiers -->
    <div class="appbar folders-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('documents.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Dossiers</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('document-folders.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-folder-plus"></i>
                    <span class="btn-text">Nouveau Dossier</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Champ de recherche -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par nom de dossier..." name="q" value="{{ request('q') }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
        </div>
    </form>

    <!-- Liste des dossiers -->
    <div class="folders-list">
        @forelse($folders as $index => $folder)
            <div class="folder-row {{ $index > 0 ? 'folder-row-separated' : '' }}" onclick="window.location.href='{{ route('document-folders.show', $folder) }}'" style="cursor: pointer;">
                <div class="folder-row-body">
                    <div class="folder-info">
                        <div class="folder-date">
                            <span class="badge bg-custom">{{ $folder->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="folder-name">
                            {{ $folder->name }}
                        </div>
                        <div class="folder-details">
                            <i class="bi bi-file-earmark me-1"></i>{{ $folder->documents_count }} document{{ $folder->documents_count > 1 ? 's' : '' }}
                            @if($folder->description)
                                <span class="ms-2"><i class="bi bi-chat-text me-1"></i>{{ Str::limit($folder->description, 40) }}</span>
                            @endif
                            @if($folder->is_active)
                                <span class="ms-2"><i class="bi bi-check-circle me-1"></i>Actif</span>
                            @else
                                <span class="ms-2"><i class="bi bi-x-circle me-1"></i>Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="folder-actions">
                    <a href="{{ route('document-folders.edit', $folder) }}" class="btn btn-sm btn-outline-secondary" onclick="event.stopPropagation()">Modifier</a>
                    <form action="{{ route('document-folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation()" {{ $folder->documents_count > 0 ? 'disabled' : '' }}>Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="folder-row-empty">
                <i class="bi bi-folder"></i>
                <div>Aucun dossier créé</div>
                <small class="text-muted mt-2">Commencez par créer votre premier dossier pour organiser vos documents</small>
            </div>
        @endforelse
    </div>
</div>
@endsection
