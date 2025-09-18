@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-cash-coin me-3"></i>
                    Dîmes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-wallet2 me-2"></i>
                    Gérez les dîmes des membres
                </p>
            </div>
            <div>
                <a href="{{ route('tithes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="btn-label">Nouvelle dîme</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Rechercher par membre, méthode, référence..." name="q" value="{{ $search ?? '' }}">
                    @if(!empty($search))
                    <a class="btn btn-outline-secondary" href="{{ route('tithes.index') }}">Effacer</a>
                    @endif
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> <span class="btn-label">Rechercher</span></button>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-md-auto ms-md-auto d-flex gap-2 justify-content-end">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label">Filtrer</span></button>
                <a class="btn btn-outline-secondary" href="{{ route('tithes.index') }}"><i class="bi bi-arrow-counterclockwise"></i> <span class="btn-label">Réinitialiser</span></a>
            </div>
        </div>
    </form>

    <!-- Graphique évolution des dîmes (6 derniers mois) -->
    <div class="card card-soft p-3 mb-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-graph-up"></i>
            <h2 class="h6 m-0">Évolution des dîmes ({{ $chart['year'] ?? now()->year }})</h2>
        </div>
        <div style="height:280px;">
            <canvas id="tithesLineChart"></canvas>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($tithes as $tithe)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">{{ optional($tithe->paid_at)->format('d/m/Y') }}</span>
                        <div class="text-end fw-bold numeric">{{ number_format(round($tithe->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="fw-semibold">
                        <a class="link-dark text-decoration-none" href="{{ route('members.show', $tithe->member) }}">{{ $tithe->member?->last_name }} {{ $tithe->member?->first_name }}</a>
                    </div>
                    <div class="small text-muted mt-1">
                        <i class="bi bi-wallet2 me-1"></i>{{ $tithe->payment_method ?? '—' }}
                        <span class="ms-2"><i class="bi bi-hash me-1"></i>{{ $tithe->reference ?? '—' }}</span>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('tithes.edit', $tithe) }}">Modifier</a>
                    <form action="{{ route('tithes.destroy', $tithe) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="text-center text-muted py-5">Aucune dîme</div></div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $tithes->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const el = document.getElementById('tithesLineChart');
    if (!el) return;

    const labels = @json($chart['labels_numeric'] ?? range(1,12));
    const raw = @json($chart['data'] ?? []);
    const data = Array.from({ length: 12 }, (_, i) => Number(raw[i] ?? 0));

    const ctx = el.getContext('2d');
    const h = 280;
    const gradient = ctx.createLinearGradient(0, 0, 0, h);
    gradient.addColorStop(0, 'rgba(255,38,0,0.3)');
    gradient.addColorStop(1, 'rgba(255,38,0,0.05)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Dîmes',
                data,
                borderColor: '#FF2600',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#FF2600',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#FF2600',
                    borderWidth: 1,
                    callbacks: { 
                        label: (ctx) => `${ctx.dataset.label}: ${Math.round(Number(ctx.parsed.y)).toLocaleString('fr-FR')} FCFA`
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                    ticks: { color: '#6B7280' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#6B7280' }
                }
            },
            animation: { duration: 800, easing: 'easeInOutQuart' }
        }
    });
});
</script>
@endpush


