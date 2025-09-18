@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Dépenses</h1>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">Nouvelle dépense</a>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <!-- Filtres par date -->
    <form class="card card-soft p-3 mb-3" method="GET" action="{{ route('expenses.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-4 col-md-6 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
                <a class="btn btn-outline-secondary" href="{{ route('expenses.index') }}"><i class="bi bi-arrow-counterclockwise"></i> <span class="btn-label">Réinitialiser</span></a>
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

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($expenses as $e)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">{{ optional($e->paid_at)->format('d/m/Y') }}</span>
                        <div class="fw-bold numeric">{{ number_format(round($e->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="fw-semibold">{{ $e->project?->name ?? '—' }}</div>
                    <div class="small text-muted mt-1">{{ $e->category }}</div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('expenses.edit', $e) }}">Modifier</a>
                    <form action="{{ route('expenses.destroy', $e) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucune dépense</div></div>
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


