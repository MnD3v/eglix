@extends('layouts.app')

@section('title', 'Gestion des Dossiers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active">Dossiers</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-folder-multiple-outline me-1"></i>
                    Gestion des Dossiers
                </h4>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('document-folders.create') }}" class="btn btn-primary">
                <i class="mdi mdi-folder-plus"></i> Nouveau Dossier
            </a>
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-file-document-outline"></i> Voir les Documents
            </a>
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
