@extends('layouts.app')

@section('title', 'Gestion des Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Documents</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-folder-multiple-outline me-1"></i>
                    Gestion des Documents
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="btn-group" role="group">
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Nouveau Document
                </a>
                <a href="{{ route('document-folders.create') }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-folder-plus"></i> Nouveau Dossier
                </a>
                <a href="{{ route('document-folders.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-folder-multiple"></i> Gérer les Dossiers
                </a>
            </div>
        </div>
        <div class="col-md-4">
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
    </div>

    <!-- Statistiques -->
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
                                    <i class="mdi mdi-folder-multiple"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $folders->count() }}</h5>
                            <p class="text-muted mb-0">Dossiers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    <div class="row">
        @forelse($documents as $document)
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            @if($document->is_image)
                                <img src="{{ $document->thumbnail_url }}" alt="{{ $document->name }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 150px; width: 100%; object-fit: cover;">
                            @else
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-{{ $document->is_pdf ? 'danger' : 'secondary' }}-subtle text-{{ $document->is_pdf ? 'danger' : 'secondary' }} rounded">
                                        <i class="mdi mdi-{{ $document->is_pdf ? 'file-pdf-box' : 'file-document-outline' }} font-size-24"></i>
                                    </div>
                                </div>
                            @endif
                            
                            <h5 class="card-title mb-1">{{ $document->name }}</h5>
                            <p class="text-muted mb-2">
                                <small>
                                    <i class="mdi mdi-folder-outline"></i> {{ $document->folder->name }}<br>
                                    <i class="mdi mdi-file-outline"></i> {{ $document->formatted_size }}
                                </small>
                            </p>
                            
                            @if($document->description)
                                <p class="text-muted small mb-3">{{ Str::limit($document->description, 50) }}</p>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-outline-success">
                                <i class="mdi mdi-download"></i>
                            </a>
                            <a href="{{ route('documents.edit', $document) }}" class="btn btn-sm btn-outline-warning">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
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
