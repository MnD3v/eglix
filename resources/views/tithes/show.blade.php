@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('partials.back-button')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Dîme du {{ optional($tithe->paid_at)->format('d/m/Y') }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('tithes.edit', $tithe) }}">Modifier</a>
        </div>
    </div>

    <div class="card p-3">
        <div><strong>Membre:</strong> <a href="{{ route('members.show', $tithe->member) }}">{{ $tithe->member?->last_name }} {{ $tithe->member?->first_name }}</a></div>
        <div><strong>Montant:</strong> {{ number_format($tithe->amount, 2, ',', ' ') }}</div>
        <div><strong>Méthode:</strong> {{ $tithe->payment_method ?? '—' }}</div>
        <div><strong>Référence:</strong> {{ $tithe->reference ?? '—' }}</div>
        <div><strong>Notes:</strong> {{ $tithe->notes ?? '—' }}</div>
    </div>
</div>
@endsection


