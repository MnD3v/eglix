@extends('layouts.app')
@section('content')
<div class="container py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Dépense du {{ optional($expense->paid_at)->format('d/m/Y') }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('expenses.edit', $expense) }}">Modifier</a>
    </div>
    <div class="card p-3">
        <div><strong>Projet:</strong> {{ $expense->project?->name ?? '—' }}</div>
        <div><strong>Catégorie:</strong> {{ $expense->category }}</div>
        <div><strong>Montant:</strong> {{ number_format($expense->amount, 2, ',', ' ') }}</div>
        <div><strong>Fournisseur:</strong> {{ $expense->vendor ?? '—' }}</div>
        <div><strong>Référence:</strong> {{ $expense->reference ?? '—' }}</div>
        <div><strong>Description:</strong> {{ $expense->description ?? '—' }}</div>
        <div><strong>Notes:</strong> {{ $expense->notes ?? '—' }}</div>
    </div>
</div>
@endsection


