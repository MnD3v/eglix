@extends('layouts.app')
@section('content')
<style>
/* Styles pour la liste des dépenses */
.expenses-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.expense-row {
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

.expense-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.expense-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.expense-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.expense-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.expense-date {
    margin-bottom: 4px;
}

.expense-project {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.expense-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.expense-amount {
    flex-shrink: 0;
    text-align: right;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.expense-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.expense-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.expense-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Styles pour les champs de recherche arrondis */
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

/* Icônes noires dans toute la section dépenses */
.expenses-list .bi,
.expenses-appbar .bi,
.expense-details .bi,
.expense-row-empty .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.expense-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Dépenses -->
    <div class="appbar expenses-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Dépenses</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('expenses.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouvelle dépense</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <!-- Filtres par date -->
    <form class="card card-soft p-3 mb-3" method="GET" action="{{ route('expenses.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control date-input" />
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control date-input" />
            </div>
            <div class="col-12 col-sm-4 col-md-6 d-flex gap-2">
                <button class="btn btn filter-btn" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
            </div>
        </div>
    </form>

    <!-- Graphique évolution des dépenses (année en cours) -->
    <div class="card card-soft p-3 mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-graph-down"></i>
            <h2 class="h6 m-0">Évolution des dépenses ({{ $chart['year'] ?? now()->year }})</h2>
        </div>
        <div style="height:280px;">
            <canvas id="expensesLineChart"></canvas>
        </div>
    </div>

    <div class="expenses-list">
        @forelse($expenses as $index => $e)
            <div class="expense-row {{ $index > 0 ? 'expense-row-separated' : '' }}">
                <div class="expense-row-body">
                    <div class="expense-info">
                        <div class="expense-date">
                            <span class="badge bg-custom">{{ optional($e->paid_at)->format('d/m/Y') }}</span>
                        </div>
                        <div class="expense-project">
                            {{ $e->project?->name ?? 'Dépense générale' }}
                        </div>
                        <div class="expense-details">
                            <i class="bi bi-receipt me-1"></i>{{ $e->description ?? 'Aucune description' }}
                        </div>
                    </div>
                    <div class="expense-amount">
                        <div class="amount-value">{{ number_format(round($e->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                <div class="expense-actions">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('expenses.edit', $e) }}">Modifier</a>
                    <form action="{{ route('expenses.destroy', $e) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="expense-row-empty">
                <i class="bi bi-receipt"></i>
                <div>Aucune dépense trouvée</div>
                <small class="text-muted mt-2">Commencez par enregistrer votre première dépense</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $expenses->links() }}</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const labels = @json(collect($chart['labels'] ?? [])->map(fn($m) => (int) \Carbon\Carbon::createFromFormat('Y-m', $m)->format('n')));
    const data = @json($chart['data'] ?? []);
    const ctx = document.getElementById('expensesLineChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Dépenses',
                data,
                borderColor: '#F97316',
                backgroundColor: 'rgba(249,115,22,.12)',
                tension: .3,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#F97316',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => `${Math.round(Number(ctx.parsed.y)).toLocaleString('fr-FR')} FCFA` } }
            },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush


