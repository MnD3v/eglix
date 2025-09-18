@extends('layouts.app')
@section('content')

<style>
/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6B7280;
    font-size: 1rem;
    margin-bottom: 0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-bottom: 2rem;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn.primary {
    background: #8B5CF6;
    color: white;
    border-color: #8B5CF6;
}

.action-btn.primary:hover {
    background: #7C3AED;
    border-color: #7C3AED;
    color: white;
}

/* Transaction Card */
.transaction-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.transaction-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.transaction-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.transaction-icon.transparency { background: #8B5CF6; }

.transaction-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0;
}

.transaction-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin: 0;
}

/* Transaction List */
.transaction-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.transaction-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.transaction-item:hover {
    background: #F3F4F6;
}

.transaction-date {
    background: #8B5CF6;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 60px;
    text-align: center;
    flex-shrink: 0;
}

.transaction-detail {
    flex: 1;
    min-width: 0;
}

.transaction-name {
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.transaction-type {
    font-size: 0.75rem;
    color: #6B7280;
}

.transaction-amount {
    font-weight: 700;
    flex-shrink: 0;
}

.transaction-amount.positive {
    color: #22C55E;
}

.transaction-amount.negative {
    color: #EF4444;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6B7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Transparence Financière</h1>
        <p class="page-subtitle">Vue consolidée des écritures récentes : dîmes, offrandes, dons et dépenses</p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('reports.index') }}" class="action-btn">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
        <a href="{{ route('reports.transparency.pdf') }}" class="action-btn primary">
            <i class="bi bi-download"></i>
            Télécharger PDF
        </a>
    </div>

    @if($entries->count() > 0)
        <div class="transaction-card">
            <div class="transaction-header">
                <div class="transaction-icon transparency">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h3 class="transaction-title">Transactions Récentes</h3>
                    <p class="transaction-description">Historique détaillé des 50 dernières transactions</p>
                </div>
            </div>
            <div class="transaction-list">
                @foreach($entries as $entry)
                <div class="transaction-item">
                    <div class="transaction-date">{{ $entry['date'] }}</div>
                    <div class="transaction-detail">
                        <div class="transaction-name">{{ $entry['detail'] }}</div>
                        <div class="transaction-type">{{ $entry['type'] }}</div>
                    </div>
                    <div class="transaction-amount {{ $entry['sign'] === '+' ? 'positive' : 'negative' }}">
                        {{ $entry['sign'] }}{{ number_format(round($entry['amount']), 0, ',', ' ') }} FCFA
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-receipt"></i>
            <h3>Aucune transaction</h3>
            <p>Aucune écriture financière n'a été enregistrée pour le moment.</p>
        </div>
    @endif
</div>
@endsection