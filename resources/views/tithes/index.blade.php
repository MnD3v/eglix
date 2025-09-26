@extends('layouts.app')

@section('content')
<style>
/* Styles pour la liste des dîmes */
.tithes-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.tithe-row {
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

.tithe-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.tithe-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.tithe-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.tithe-date {
    flex-shrink: 0;
}

.tithe-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.tithe-date {
    margin-bottom: 4px;
}

.tithe-member {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.tithe-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tithe-amount {
    flex-shrink: 0;
    text-align: right;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.tithe-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.tithe-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.tithe-row-empty i {
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

/* Icônes noires dans toute la section dîmes */
.tithes-list .bi,
.tithes-appbar .bi,
.tithe-details .bi,
.tithe-row-empty .bi,
.search-icon .bi,
.search-btn .bi,
.filter-btn .bi {
    color: #000000 !important;
}

/* Texte de date noir */
.tithe-date .badge {
    color: #000000 !important;
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
}
</style>
<div class="container py-4">
    <!-- AppBar Dîmes -->
    <div class="appbar tithes-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Dîmes</h1>
                </div>
            </div>
            <div class="appbar-right">
                <div class="total-amount-display me-2">
                    <div class="total-amount-text">{{ number_format($totalAmount, 0, ',', ' ') }} FCFA</div>
                    <small class="total-amount-label">Total des dîmes</small>
                </div>
                <a href="{{ route('tithes.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-text">Nouvelle dîme</span>
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
                <div class="input-group search-group">
                    <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control search-input" placeholder="Rechercher par membre, méthode, référence..." name="q" value="{{ $search ?? '' }}">
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

    <div class="tithes-list">
        @forelse($tithes as $index => $tithe)
            <div class="tithe-row {{ $index > 0 ? 'tithe-row-separated' : '' }}">
                <div class="tithe-row-body">
                    <div class="tithe-info">
                        <div class="tithe-date">
                            <span class="badge bg-custom">{{ optional($tithe->paid_at)->format('d/m/Y') }}</span>
                        </div>
                        <div class="tithe-member">
                            <a class="link-dark text-decoration-none" href="{{ route('members.show', $tithe->member) }}">{{ $tithe->member?->last_name }} {{ $tithe->member?->first_name }}</a>
                        </div>
                        <div class="tithe-details">
                            <i class="bi bi-wallet2 me-1"></i>{{ $tithe->payment_method ?? '—' }}
                            <span class="ms-2"><i class="bi bi-hash me-1"></i>{{ $tithe->reference ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="tithe-amount">
                        <div class="amount-value">{{ number_format(round($tithe->amount), 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                <div class="tithe-actions">
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('tithes.edit', $tithe) }}">Modifier</a>
                    <form action="{{ route('tithes.destroy', $tithe) }}" method="POST" data-confirm="Supprimer ?" data-confirm-ok="Supprimer" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="tithe-row-empty">
                <i class="bi bi-cash-coin"></i>
                <div>Aucune dîme trouvée</div>
                <small class="text-muted mt-2">Commencez par enregistrer votre première dîme</small>
            </div>
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
                borderColor: '#FFCC00',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#FFCC00',
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
                    borderColor: '#FFCC00',
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


