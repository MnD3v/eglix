@extends('layouts.app')
@section('content')
<div class="container py-4">
    @include('partials.back-button')
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Don du {{ optional($donation->received_at)->format('d/m/Y') }}</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('donations.edit', $donation) }}"><i class="bi bi-pencil me-1"></i>Modifier</a>
            <a class="btn btn-outline-primary" href="{{ route('donations.index') }}">Retour</a>
        </div>
    </div>
    <div class="card card-soft p-3">
        <div class="row g-3">
            <div class="col-md-6"><div class="small text-muted">Membre</div><div class="fw-semibold">{{ $donation->member?->last_name }} {{ $donation->member?->first_name }}</div></div>
            <div class="col-md-6"><div class="small text-muted">Donateur</div><div class="fw-semibold">{{ $donation->donor_name ?? '—' }}</div></div>
            <div class="col-md-6"><div class="small text-muted">Projet</div><div class="fw-semibold">{{ $donation->project?->name ?? '—' }}</div></div>
            @if($donation->donation_type==='money')
                <div class="col-md-6"><div class="small text-muted">Montant</div><div class="fw-bold numeric">{{ number_format(round($donation->amount), 0, ',', ' ') }} FCFA</div></div>
                <div class="col-md-6"><div class="small text-muted">Méthode</div><div class="fw-semibold">{{ $donation->payment_method ?? '—' }}</div></div>
            @else
                <div class="col-md-6"><div class="small text-muted">Objet</div><div class="fw-semibold">{{ $donation->physical_item }}</div></div>
                <div class="col-12"><div class="small text-muted">Description</div><div class="fw-semibold">{{ $donation->physical_description ?? '—' }}</div></div>
            @endif
            <div class="col-md-6"><div class="small text-muted">Référence</div><div class="fw-semibold">{{ $donation->reference ?? '—' }}</div></div>
            <div class="col-12"><div class="small text-muted">Notes</div><div class="fw-semibold">{{ $donation->notes ?? '—' }}</div></div>
        </div>
    </div>
</div>
@endsection


