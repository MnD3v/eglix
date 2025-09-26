@extends('layouts.app')
@section('content')
<style>
/* Styles pour les champs de formulaire arrondis */
.form-control, .form-select, .form-label {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les boutons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Styles pour la liste */
.function-types-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.function-type-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.function-type-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.function-type-row-separated {
    border-top: 1px solid #e2e8f0;
}

.function-type-row-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.function-type-info {
    flex: 1;
}

.function-type-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.function-type-details {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
    color: #64748b;
}

.function-type-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.function-type-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.function-type-row-empty i {
    font-size: 3rem;
    opacity: 0.3;
    margin-bottom: 1rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .function-type-row-body {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .function-type-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Types de Fonctions -->
    <div class="appbar administration-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('administration.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Types de Fonctions</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('administration-function-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Nouveau Type
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="function-types-list">
        @forelse($functionTypes as $index => $type)
        <div class="function-type-row {{ $index > 0 ? 'function-type-row-separated' : '' }}">
            <div class="function-type-row-body">
                <div class="function-type-info">
                    <div class="function-type-name">{{ $type->name }}</div>
                    <div class="function-type-details">
                        <span class="badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $type->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        <span><i class="bi bi-sort-numeric-up me-1"></i>#{{ $type->sort_order }}</span>
                        <span><i class="bi bi-people me-1"></i>{{ $type->active_functions_count }} membre{{ $type->active_functions_count > 1 ? 's' : '' }} actif{{ $type->active_functions_count > 1 ? 's' : '' }}</span>
                    </div>
                    @if($type->description)
                        <div class="mt-2 text-muted small">{{ Str::limit($type->description, 100) }}</div>
                    @endif
                </div>
                <div class="function-type-actions">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('administration-function-types.show', $type) }}">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('administration-function-types.edit', $type) }}">
                        <i class="bi bi-pencil"></i>
                    </a>
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
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="function-type-row function-type-row-empty">
            <i class="bi bi-tags"></i>
            <p>Aucun type de fonction</p>
        </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $functionTypes->links() }}
    </div>
</div>
@endsection
