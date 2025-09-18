@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Types de fonctions</h1>
        <a href="{{ route('administration-function-types.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> <span class="btn-label">Nouveau type</span></a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($functionTypes as $type)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $type->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        <small class="text-muted">#{{ $type->sort_order }}</small>
                    </div>
                    
                    <h5 class="card-title mb-2">{{ $type->name }}</h5>
                    
                    @if($type->description)
                        <p class="small text-muted mb-2">{{ Str::limit($type->description, 100) }}</p>
                    @endif
                    
                    <div class="small text-muted">
                        <i class="bi bi-people me-1"></i>
                        {{ $type->active_functions_count }} membre{{ $type->active_functions_count > 1 ? 's' : '' }} actif{{ $type->active_functions_count > 1 ? 's' : '' }}
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('administration-function-types.show', $type) }}">Voir</a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('administration-function-types.edit', $type) }}">Modifier</a>
                    <form action="{{ route('administration-function-types.toggle', $type) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm {{ $type->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                title="{{ $type->is_active ? 'Désactiver' : 'Activer' }}">
                            <i class="bi bi-{{ $type->is_active ? 'pause' : 'play' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('administration-function-types.destroy', $type) }}" method="POST" data-confirm="Supprimer ce type de fonction ?" data-confirm-ok="Supprimer">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center text-muted py-5">
                <i class="bi bi-tags" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3">Aucun type de fonction</p>
                <a href="{{ route('administration-function-types.create') }}" class="btn btn-primary">Créer le premier type</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $functionTypes->links() }}
    </div>
</div>
@endsection
