@extends('layouts.app')

@section('content')
<style>
/* Styles pour la liste des offrandes */
.offerings-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.offering-row {
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

.offering-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.offering-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.offering-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.offering-date {
    flex-shrink: 0;
}

.offering-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.offering-date {
    margin-bottom: 4px;
}

.offering-type {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.offering-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.offering-amount {
    flex-shrink: 0;
    text-align: right;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.offering-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Soft Elevated Buttons pour les offrandes */
.offering-actions .btn {
    border-radius: 12px;
    font-weight: 600;
    font-size: 13px;
    padding: 8px 16px;
    transition: all 0.2s ease;
    border-width: 1.5px;
}

.offering-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.offering-actions .btn-outline-secondary {
    background: #ffffff;
    color: #64748b;
    border-color: #e2e8f0;
}

.offering-actions .btn-outline-secondary:hover {
    background: #f8fafc;
    color: #1e293b;
    border-color: #cbd5e1;
}

.offering-actions .btn-outline-danger {
    background: #ffffff;
    color: #dc2626;
    border-color: #fecaca;
}

.offering-actions .btn-outline-danger:hover {
    background: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
}

.offering-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.offering-row-empty i {
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

/* Icônes noires dans toute la section offrandes */
.offerings-list .bi,
.offerings-appbar .bi,
.offering-details .bi,
.offering-row-empty .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.offering-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Offrandes -->
    <div class="appbar offerings-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Offrandes</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('offering-types.index') }}" class="appbar-btn-white me-2">
                    <i class="bi bi-tags"></i>
                    <span class="btn-text">Types d'offrande</span>
                </a>
                <a href="{{ route('offerings.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Ajouter une offrande</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <!-- Filtres par date -->
    <form class="card card-soft p-3 mb-3" method="GET" action="{{ route('offerings.index') }}">
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
                <button class="btn btn filter-btn" type="submit">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Graphique évolution des offrandes (année en cours) -->
    <div class="card card-soft p-3 mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-graph-up"></i>
            <h2 class="h6 m-0">Évolution des offrandes ({{ $chart['year'] ?? now()->year }})</h2>
        </div>
        <div style="height:280px;">
            <canvas id="offeringsLineChart"></canvas>
        </div>
    </div>

    <div class="offerings-list">
        @forelse($offerings as $index => $o)
            <div class="offering-row {{ $index > 0 ? 'offering-row-separated' : '' }}">
                <div class="offering-row-body">
                    <div class="offering-info">
                        <div class="offering-date">
                            <span class="badge bg-custom">{{ optional($o->received_at)->format('d/m/Y') }}</span>
                        </div>
                        <div class="offering-type">
                            {{ ucfirst(str_replace('_',' ', $o->type ?? '—')) }}
                        </div>
                        <div class="offering-details">
                            <i class="bi bi-wallet2 me-1"></i>{{ $o->payment_method ?? '—' }}
                        </div>
                    </div>
                    <div class="offering-amount">
                        <div class="amount-value">{{ number_format(round($o->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                <div class="offering-actions">
                    <a class="btn btn-outline-secondary" href="{{ route('offerings.edit', $o) }}" title="Modifier l'offrande">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form action="{{ route('offerings.destroy', $o) }}" method="POST" data-confirm="Supprimer cette offrande ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger" title="Supprimer l'offrande">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="offering-row-empty">
                <i class="bi bi-gift"></i>
                <div>Aucune offrande trouvée</div>
                <small class="text-muted mt-2">Commencez par enregistrer votre première offrande</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $offerings->links() }}</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const labels = @json(collect($chart['labels'] ?? [])->map(fn($m) => (int) \Carbon\Carbon::createFromFormat('Y-m', $m)->format('n')));
    const data = @json($chart['data'] ?? []);
    const ctx = document.getElementById('offeringsLineChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Offrandes',
                data,
                borderColor: '#0EA5E9',
                backgroundColor: 'rgba(14,165,233,.12)',
                tension: .3,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#0EA5E9',
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


