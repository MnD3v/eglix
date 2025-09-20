@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
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
            <div class="d-flex flex-column gap-3 w-100 w-lg-auto">
                <div class="text-start total-amount">
                    <div class="h4 mb-0 text-white" id="total-amount">{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</div>
                    <small class="text-white-50">Total des dîmes</small>
                </div>
                <a href="{{ route('tithes.create') }}" class="btn btn-sm btn w-100">
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
        <div class="row g-2 g-lg-3 align-items-end">
            <div class="col-12 col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Rechercher par membre, méthode, référence..." name="q" value="{{ $search ?? '' }}">
                    @if(!empty($search))
                    <a class="btn btn-outline-secondary" href="{{ route('tithes.index') }}">Effacer</a>
                    @endif
                    <button class="btn btn" type="submit"><i class="bi bi-search"></i> <span class="btn-label d-none d-lg-inline">Rechercher</span></button>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <label class="form-label small text-muted">Du</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-6 col-lg-3">
                <label class="form-label small text-muted">Au</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
            </div>
            <div class="col-12 col-lg-auto ms-lg-auto d-flex gap-2 justify-content-end">
                <button class="btn btn" type="submit"><i class="bi bi-funnel"></i> <span class="btn-label d-none d-lg-inline">Filtrer</span></button>
                <a class="btn btn-outline-secondary" href="{{ route('tithes.index') }}"><i class="bi bi-arrow-counterclockwise"></i> <span class="btn-label d-none d-lg-inline">Réinitialiser</span></a>
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

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-2 g-lg-3">
        @forelse($tithes as $tithe)
        <div class="col">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-custom">{{ optional($tithe->paid_at)->format('d/m/Y') }}</span>
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
    // Fonction pour mettre à jour le total des dîmes
    function updateTotal() {
        const fromInput = document.querySelector('input[name="from"]');
        const toInput = document.querySelector('input[name="to"]');
        const totalElement = document.getElementById('total-amount');
        
        if (!totalElement) return;
        
        // Afficher un indicateur de chargement
        totalElement.innerHTML = '<i class="bi bi-hourglass-split"></i> Calcul...';
        
        // Construire l'URL avec les paramètres
        const params = new URLSearchParams();
        if (fromInput && fromInput.value) {
            params.append('from', fromInput.value);
        }
        if (toInput && toInput.value) {
            params.append('to', toInput.value);
        }
        
        // Faire la requête AJAX
        fetch(`{{ route('tithes.total') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                totalElement.textContent = data.formatted;
            })
            .catch(error => {
                console.error('Erreur lors du calcul du total:', error);
                totalElement.textContent = 'Erreur';
            });
    }
    
    // Écouter les changements sur les filtres de date
    const fromInput = document.querySelector('input[name="from"]');
    const toInput = document.querySelector('input[name="to"]');
    
    if (fromInput) {
        fromInput.addEventListener('change', updateTotal);
    }
    if (toInput) {
        toInput.addEventListener('change', updateTotal);
    }
    
    // Écouter la soumission du formulaire de filtrage
    const filterForm = document.querySelector('form[method="GET"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Laisser le formulaire se soumettre normalement
            // Le total sera mis à jour par le serveur
        });
    }
    
    // Graphique des dîmes
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


