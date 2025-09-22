@extends('layouts.app')

@section('title', 'Modifier le Document')

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
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="mdi mdi-file-edit me-1"></i>
                    Modifier le Document
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('documents.update', $document) }}" method="POST" id="documentForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du document <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $document->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="folder_id" class="form-label">Dossier <span class="text-danger">*</span></label>
                                    <select class="form-select @error('folder_id') is-invalid @enderror" 
                                            id="folder_id" name="folder_id" required>
                                        @foreach($folders as $folder)
                                            <option value="{{ $folder->id }}" 
                                                    {{ old('folder_id', $document->folder_id) == $folder->id ? 'selected' : '' }}>
                                                {{ $folder->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('folder_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $document->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_public" name="is_public" value="1" 
                                       {{ old('is_public', $document->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Document public (accessible sans authentification)
                                </label>
                            </div>
                        </div>

                        <!-- Informations sur le fichier actuel -->
                        <div class="mb-3">
                            <label class="form-label">Fichier actuel</label>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        @if($document->is_image)
                                            <img src="{{ $document->thumbnail_url }}" alt="{{ $document->name }}" 
                                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-{{ $document->is_pdf ? 'danger' : 'secondary' }}-subtle text-{{ $document->is_pdf ? 'danger' : 'secondary' }} rounded">
                                                    <i class="mdi mdi-{{ $document->is_pdf ? 'file-pdf-box' : 'file-document-outline' }} font-size-18"></i>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $document->original_name }}</h6>
                                            <p class="text-muted mb-0 small">
                                                {{ $document->formatted_size }} • {{ strtoupper($document->file_extension) }}
                                            </p>
                                        </div>
                                        <div>
                                            <a href="{{ route('documents.download', $document) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Mettre à jour le Document
                            </button>
                        </div>
                    </form>
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
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Modification :</h6>
                        <ul class="mb-0">
                            <li>Vous pouvez modifier le nom et la description</li>
                            <li>Vous pouvez changer le dossier de destination</li>
                            <li>Vous pouvez modifier le statut public/privé</li>
                            <li>Le fichier lui-même ne peut pas être modifié</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Pour changer le fichier :</h6>
                        <p class="mb-2">Si vous souhaitez remplacer le fichier, vous devez :</p>
                        <ol class="mb-0">
                            <li>Supprimer ce document</li>
                            <li>Créer un nouveau document avec le nouveau fichier</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <h6>Statistiques</h6>
                        <ul class="list-unstyled">
                            <li><strong>Créé le :</strong> {{ $document->created_at->format('d/m/Y à H:i') }}</li>
                            <li><strong>Modifié le :</strong> {{ $document->updated_at->format('d/m/Y à H:i') }}</li>
                            @if($document->creator)
                                <li><strong>Créé par :</strong> {{ $document->creator->name }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
