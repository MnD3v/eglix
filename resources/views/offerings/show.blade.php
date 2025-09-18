@extends('layouts.app')
@section('content')
<div class="container py-4">
    @include('partials.back-button')
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Offrande du {{ optional($offering->received_at)->format('d/m/Y') }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('offerings.edit', $offering) }}">Modifier</a>
    </div>
    <div class="card p-3">
        <div><strong>Membre:</strong> {{ $offering->member?->last_name }} {{ $offering->member?->first_name }}</div>
        <div><strong>Type:</strong> {{ $offering->type ?? '—' }}</div>
        <div><strong>Montant:</strong> {{ number_format($offering->amount, 2, ',', ' ') }}</div>
        <div><strong>Méthode:</strong> {{ $offering->payment_method ?? '—' }}</div>
        <div><strong>Référence:</strong> {{ $offering->reference ?? '—' }}</div>
        <div><strong>Notes:</strong> {{ $offering->notes ?? '—' }}</div>
    </div>
</div>
@endsection


