@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-music-note-beamed me-3"></i>
                    Cultes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-calendar-check me-2"></i>
                    Gérez les cultes et services de l'église
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('service-roles.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-badge me-2"></i>
                    <span class="btn-label">Rôles</span>
                </a>
                <a href="{{ route('services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="btn-label">Nouveau culte</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Date</th><th>Type</th><th>Thème</th><th>Lieu</th><th>Rôles</th><th></th></tr></thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}</td>
                    <td>
                        @if($s->type)
                            <span class="badge bg-primary">{{ $s->type }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $s->theme ?? '—' }}</td>
                    <td>{{ $s->location ?? '—' }}</td>
                    <td>
                        @php
                            $assignmentsCount = $s->assignments()->count();
                        @endphp
                        @if($assignmentsCount > 0)
                            <span class="badge bg-success">{{ $assignmentsCount }} rôle{{ $assignmentsCount > 1 ? 's' : '' }}</span>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('services.program', $s) }}" title="Programmer">
                            <i class="bi bi-person-badge"></i>
                        </a>
                        <a class="btn btn-sm btn-outline-secondary me-1" href="{{ route('services.edit', $s) }}">Modifier</a>
                        <form action="{{ route('services.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Aucun culte</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $services->links() }}
</div>
@endsection


