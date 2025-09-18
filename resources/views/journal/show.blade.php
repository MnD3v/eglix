@extends('layouts.app')
@section('content')
<div class="container py-4">
    @include('partials.back-button')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">{{ $entry->title }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('journal.edit', $entry) }}" class="btn btn-outline-secondary">Modifier</a>
            <a href="{{ route('journal.index') }}" class="btn btn-primary">Journal</a>
        </div>
    </div>

    <div class="card card-soft p-3 mb-3">
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary">{{ optional($entry->occurred_at)->format('d/m/Y') }}</span>
            <span class="text-muted">{{ $entry->category ?? '—' }}</span>
        </div>
        <div class="mt-3" style="white-space: pre-wrap;">{{ $entry->description }}</div>
    </div>

    @if($entry->images->count())
    <div class="card card-soft p-3">
        <h2 class="h6 mb-3">Galerie</h2>
        <div class="row g-3">
            @foreach($entry->images as $img)
            <div class="col-6 col-md-3">
                <a href="{{ asset('storage/'.$img->path) }}" target="_blank">
                    <img src="{{ asset('storage/'.$img->path) }}" alt="Image" class="img-fluid rounded" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection


