@extends('layouts.app')

@section('title', 'Gestion des Dossiers')

@section('content')
<div class="container-fluid">
    <!-- Header de section moderne -->
    <div class="section-header">
        <h1 class="section-title">
            <div class="section-title-icon">
                <i class="mdi mdi-folder-multiple"></i>
            </div>
            Dossiers
        </h1>
        <p class="section-subtitle">
            <i class="mdi mdi-shield-check section-subtitle-icon"></i>
            Organisez vos documents en dossiers thématiques pour une meilleure gestion
        </p>
        
        <div class="section-actions">
            <a href="{{ route('document-folders.create') }}" class="section-action-btn">
                <i class="mdi mdi-plus"></i>
                Nouveau Dossier
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
                            <i class="mdi mdi-file-document-outline me-1"></i>Documents
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="mdi mdi-folder-multiple me-1"></i>Dossiers
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Liste des dossiers -->
    <div class="row">
        @forelse($folders as $folder)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded" style="background-color: {{ $folder->color }}20;">
                                    <span class="avatar-title rounded" style="background-color: {{ $folder->color }}; color: white;">
                                        <i class="mdi mdi-folder-outline font-size-18"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1">{{ $folder->name }}</h5>
                                <p class="text-muted small mb-2">
                                    {{ $folder->documents_count }} document{{ $folder->documents_count > 1 ? 's' : '' }}
                                </p>
                                @if($folder->description)
                                    <p class="text-muted small mb-2">{{ Str::limit($folder->description, 60) }}</p>
                                @endif
                                <div class="d-flex align-items-center">
                                    @if($folder->is_active)
                                        <span class="badge bg-success-subtle text-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Inactif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between">
                            <a href="{{ route('document-folders.show', $folder) }}" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye"></i> Voir
                            </a>
                            <div class="btn-group" role="group">
                                <a href="{{ route('document-folders.edit', $folder) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <form action="{{ route('document-folders.destroy', $folder) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?')"
                                            {{ $folder->documents_count > 0 ? 'disabled' : '' }}>
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
                            <i class="mdi mdi-folder-multiple-outline font-size-24"></i>
                        </div>
                    </div>
                    <h4>Aucun dossier créé</h4>
                    <p class="text-muted">Commencez par créer votre premier dossier pour organiser vos documents.</p>
                    <a href="{{ route('document-folders.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-folder-plus"></i> Créer un Dossier
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
