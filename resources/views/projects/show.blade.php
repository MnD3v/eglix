@extends('layouts.app')
@section('content')
<div class="container py-4">
    @include('partials.back-button')
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">{{ $project->name }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('projects.edit', $project) }}">Modifier</a>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card p-3 h-100">
                <div><strong>Statut:</strong> {{ $project->status }}</div>
                <div><strong>Budget:</strong> {{ number_format($project->budget ?? 0, 2, ',', ' ') }}</div>
                <div><strong>Période:</strong> {{ optional($project->start_date)->format('d/m/Y') }} — {{ optional($project->end_date)->format('d/m/Y') }}</div>
                <div><strong>Description:</strong> {{ $project->description ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection


