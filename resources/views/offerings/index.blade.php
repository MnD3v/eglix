@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-gift me-3"></i>
                    Offrandes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-heart me-2"></i>
                    Gérez les offrandes des membres
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('offerings.create') }}" class="btn btn">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="btn-label">Ajouter une offrande</span>
                </a>
                <a href="{{ route('offering-types.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-tags me-2"></i>
                    <span class="btn-label">Types d'offrande</span>
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
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-4 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-sm-4 col-md-6 d-flex gap-2">
                <button class="btn btn" type="submit">Filtrer</button>
                <a class="btn btn-outline-secondary" href="{{ route('offerings.index') }}">Réinitialiser</a>
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

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($offerings as $o)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-custom">{{ optional($o->received_at)->format('d/m/Y') }}</span>
                        <div class="fw-bold numeric">{{ number_format(round($o->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="fw-semibold">{{ ucfirst(str_replace('_',' ', $o->type ?? '—')) }}</div>
                    <div class="small text-muted mt-1">{{ $o->payment_method ?? '—' }}</div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('offerings.edit', $o) }}">Modifier</a>
                    <form action="{{ route('offerings.destroy', $o) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucune offrande</div></div>
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


