@extends('layouts.app')
@section('content')
<style>
/* Styles pour la liste du journal */
.journal-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.journal-row {
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

.journal-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.journal-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.journal-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.journal-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.journal-date {
    margin-bottom: 4px;
}

.journal-title {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.journal-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.journal-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.journal-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.journal-row-empty i {
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

.date-input {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.date-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

/* Icônes noires dans toute la section journal */
.journal-list .bi,
.journal-appbar .bi,
.journal-details .bi,
.journal-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.journal-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Journal -->
    <div class="appbar journal-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Journal</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('journal.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouvelle entrée</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="GET" class="card card-soft p-3 mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small text-muted">Recherche</label>
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control search-input" placeholder="Titre, catégorie, description..." />
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control date-input" />
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control date-input" />
            </div>
            <div class="col-12 col-md-auto ms-md-auto d-flex gap-2 justify-content-end">
                <button class="btn btn filter-btn" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
            </div>
        </div>
    </form>

    <div class="journal-list">
        @forelse($entries as $index => $e)
            <div class="journal-row {{ $index > 0 ? 'journal-row-separated' : '' }}">
                <div class="journal-row-body">
                    <div class="journal-info">
                        <div class="journal-date">
                            <span class="badge bg-custom">{{ optional($e->occurred_at)->format('d/m/Y') }}</span>
                        </div>
                        <div class="journal-title">
                            <a href="{{ route('journal.show', $e) }}" class="link-dark text-decoration-none">{{ $e->title }}</a>
                        </div>
                        <div class="journal-details">
                            <i class="bi bi-tag me-1"></i>{{ $e->category ?? '—' }}
                            @if($e->images->count() > 0)
                                <span class="ms-2"><i class="bi bi-image me-1"></i>{{ $e->images->count() }} image(s)</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="journal-actions">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('journal.edit', $e) }}">Modifier</a>
                    <form action="{{ route('journal.destroy', $e) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="journal-row-empty">
                <i class="bi bi-journal-text"></i>
                <div>Aucune entrée trouvée</div>
                <small class="text-muted mt-2">Commencez par créer votre première entrée</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $entries->links() }}</div>
</div>
@endsection


