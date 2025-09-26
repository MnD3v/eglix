@extends('layouts.app')
@section('content')
<style>
/* Styles pour la liste des types d'offrandes */
.offering-types-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.offering-type-row {
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

.offering-type-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.offering-type-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.offering-type-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.offering-type-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.offering-type-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.offering-type-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.offering-type-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.offering-type-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.offering-type-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Icônes noires dans toute la section types d'offrandes */
.offering-types-list .bi,
.offering-types-appbar .bi,
.offering-type-details .bi,
.offering-type-row-empty .bi {
    color: #000000 !important;
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Types d'Offrandes -->
    <div class="appbar offering-types-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('offerings.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Types d'Offrandes</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('offering-types.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus"></i>
                    <span class="btn-text">Nouveau Type</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <!-- Liste des types d'offrandes -->
    <div class="offering-types-list">
        @forelse($types as $index => $t)
            <div class="offering-type-row {{ $index > 0 ? 'offering-type-row-separated' : '' }}">
                <div class="offering-type-row-body">
                    <div class="offering-type-info">
                        <div class="offering-type-name">
                            {{ $t->name }}
                        </div>
                        <div class="offering-type-details">
                            <i class="bi bi-tag me-1"></i>slug: {{ $t->slug }}
                            @if($t->is_active)
                                <span class="ms-2"><i class="bi bi-check-circle me-1"></i>Actif</span>
                            @else
                                <span class="ms-2"><i class="bi bi-x-circle me-1"></i>Inactif</span>
                            @endif
                            @if($t->color)
                                <span class="ms-2"><i class="bi bi-palette me-1"></i><span class="badge" style="background: {{ $t->color }}; width: 12px; height: 12px; border-radius: 50%; display: inline-block;"></span></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="offering-type-actions">
                    <form method="POST" action="{{ route('offering-types.toggle', $t) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary">{{ $t->is_active ? 'Désactiver' : 'Activer' }}</button>
                    </form>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('offering-types.edit', $t) }}">Modifier</a>
                    <form method="POST" action="{{ route('offering-types.destroy', $t) }}" onsubmit="return confirm('Supprimer ?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="offering-type-row-empty">
                <i class="bi bi-tags"></i>
                <div>Aucun type d'offrande</div>
                <small class="text-muted mt-2">Commencez par créer votre premier type d'offrande</small>
            </div>
        @endforelse
    </div>
</div>
@endsection


