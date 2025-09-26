@extends('layouts.app')
@section('content')

<style>
/* Styles pour la liste des dons */
.donations-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.donation-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.donation-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.donation-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.donation-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.donation-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.donation-date {
    margin-bottom: 4px;
}

.donation-donor {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.donation-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.donation-amount {
    flex-shrink: 0;
    text-align: right;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.donation-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.donation-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.donation-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
.search-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-icon {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 25px 0 0 25px;
    color: #000000;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-left: none;
    border-right: none;
    background-color: #ffffff;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 14px;
}

.search-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
    background-color: #ffffff;
}

.search-btn {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 25px 25px 0;
    color: #000000;
    font-weight: 600;
    padding: 12px 20px;
}

.search-btn:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #000000;
}

.date-input {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 14px;
}

.date-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
}

.filter-btn {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    color: #000000;
}

/* Icônes noires dans toute la section dons */
.donations-list .bi,
.donations-appbar .bi,
.donation-details .bi,
.donation-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.donation-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Dons -->
    <div class="appbar donations-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Dons</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('donations.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouveau don</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres de recherche -->
    <form method="GET" class="mb-3">
        <div class="row g-2 g-lg-3 align-items-end">
            <div class="col-12 col-lg-6">
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par donateur, type, référence..." name="q" value="{{ $search ?? '' }}">
                    <button class="btn btn search-btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control date-input" />
            </div>
            <div class="col-6 col-lg-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control date-input" />
            </div>
            <div class="col-12 col-lg-auto ms-lg-auto d-flex gap-2 justify-content-end">
                <button class="btn btn filter-btn" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label d-none d-lg-inline">Filtrer</span></button>
            </div>
        </div>
    </form>

    <div class="donations-list">
        @forelse($donations as $index => $donation)
            <div class="donation-row {{ $index > 0 ? 'donation-row-separated' : '' }}">
                <div class="donation-row-body">
                    <div class="donation-info">
                        <div class="donation-date">
                            <span class="badge bg-custom">{{ optional($donation->received_at)->format('d/m/Y') }}</span>
                        </div>
                        <div class="donation-donor">
                            <a class="link-dark text-decoration-none" href="{{ route('donations.show', $donation) }}">
                                {{ $donation->donor_name ?? ($donation->member?->last_name.' '.$donation->member?->first_name) }}
                            </a>
                        </div>
                        <div class="donation-details">
                            @if($donation->donation_type === 'money')
                                <i class="bi bi-cash-coin me-1"></i>Argent
                                @if($donation->payment_method)
                                    <span class="ms-2"><i class="bi bi-credit-card me-1"></i>{{ ucfirst($donation->payment_method) }}</span>
                                @endif
                            @else
                                <i class="bi bi-box me-1"></i>{{ $donation->physical_item }}
                            @endif
                            @if($donation->reference)
                                <span class="ms-2"><i class="bi bi-hash me-1"></i>{{ $donation->reference }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="donation-amount">
                        @if($donation->donation_type === 'money')
                            <div class="amount-value">{{ number_format(round($donation->amount), 0, ',', ' ') }} FCFA</div>
                        @else
                            <div class="amount-value">Objet physique</div>
                        @endif
                    </div>
                </div>
                <div class="donation-actions">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('donations.edit', $donation) }}">Modifier</a>
                    <form action="{{ route('donations.destroy', $donation) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="donation-row-empty">
                <i class="bi bi-heart"></i>
                <div>Aucun don trouvé</div>
                <small class="text-muted mt-2">Commencez par enregistrer votre premier don</small>
            </div>
        @endforelse
    </div>

    @if($donations->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $donations->links() }}
        </div>
    @endif
</div>
@endsection


