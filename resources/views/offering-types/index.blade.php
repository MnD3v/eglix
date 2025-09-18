@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Types d'offrande</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('offering-types.create') }}" class="btn btn-primary">Nouveau type</a>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($types as $t)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $t->is_active ? 'Actif' : 'Inactif' }}</span>
                        <span class="badge" style="background: {{ $t->color ?: '#e5e7eb' }}">&nbsp;</span>
                    </div>
                    <div class="fw-semibold">{{ $t->name }}</div>
                    <div class="small text-muted">slug: {{ $t->slug }}</div>
                </div>
                <div class="card-footer d-flex justify-content-between gap-2">
                    <form method="POST" action="{{ route('offering-types.toggle', $t) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary">{{ $t->is_active ? 'Désactiver' : 'Activer' }}</button>
                    </form>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('offering-types.edit', $t) }}">Modifier</a>
                        <form method="POST" action="{{ route('offering-types.destroy', $t) }}" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucun type</div></div>
        @endforelse
    </div>
</div>
@endsection


