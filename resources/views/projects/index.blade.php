@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Projets</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Nouveau projet</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($projects as $p)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <a href="{{ route('projects.show', $p) }}" class="fw-semibold link-dark text-decoration-none">{{ $p->name }}</a>
                        <span class="badge bg-{{ match($p->status){ 'planned' => 'secondary', 'in_progress' => 'primary', 'completed' => 'success', 'cancelled' => 'danger', default => 'secondary' } }}">{{ str_replace('_',' ', $p->status) }}</span>
                    </div>
                    <div class="small text-muted mt-2">Budget</div>
                    <div class="numeric fs-5">{{ number_format($p->budget ?? 0, 2, ',', ' ') }}</div>
                    @if($p->start_date || $p->end_date)
                    <div class="mt-2 small text-muted"><i class="bi bi-calendar3 me-1"></i>{{ optional($p->start_date)->format('d/m/Y') }} @if($p->end_date) – {{ optional($p->end_date)->format('d/m/Y') }} @endif</div>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('projects.edit', $p) }}">Modifier</a>
                    <form action="{{ route('projects.destroy', $p) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucun projet</div></div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
</div>
@endsection


