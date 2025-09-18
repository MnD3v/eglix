@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Journal</h1>
        <a href="{{ route('journal.create') }}" class="btn btn-primary">Nouvelle entrée</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="GET" class="card card-soft p-3 mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small text-muted">Recherche</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Titre, catégorie, description..." />
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control" />
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control" />
            </div>
            <div class="col-12 col-md-auto ms-md-auto d-flex gap-2 justify-content-end">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
                <a class="btn btn-outline-secondary" href="{{ route('journal.index') }}"><i class="bi bi-arrow-counterclockwise"></i> <span class="btn-label">Réinitialiser</span></a>
            </div>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($entries as $e)
        <div class="col">
            <a href="{{ route('journal.show', $e) }}" class="text-decoration-none text-reset" style="display:block;">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">{{ optional($e->occurred_at)->format('d/m/Y') }}</span>
                        <div class="small text-muted">{{ $e->category ?? '—' }}</div>
                    </div>
                    <div class="fw-semibold">{{ $e->title }}</div>
                    <div class="text-muted mt-2" style="white-space: pre-wrap;">{{ \Illuminate\Support\Str::limit($e->description, 160) }}</div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('journal.edit', $e) }}" onclick="event.stopPropagation();">Modifier</a>
                    <form action="{{ route('journal.destroy', $e) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" onsubmit="event.stopPropagation();">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
            </a>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucune entrée</div></div>
        @endforelse
    </div>

    <div class="mt-3">{{ $entries->links() }}</div>
</div>
@endsection


