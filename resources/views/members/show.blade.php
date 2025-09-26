@extends('layouts.app')

@section('content')
<div class="container py-4">
	@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
	@endif

    <!-- AppBar -->
    <div class="appbar members-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('members.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">{{ $member->last_name }} {{ $member->first_name }}</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('members.edit', $member) }}" class="appbar-btn-white me-2">
                    <i class="bi bi-pencil"></i>
                    <span class="btn-text">Modifier</span>
                </a>
                <a href="{{ route('tithes.create', ['member_id'=>$member->id]) }}" class="appbar-btn-yellow me-2">
                    <i class="bi bi-cash-coin"></i>
                    <span class="btn-text">Ajouter dîme</span>
                </a>
                <form action="{{ route('members.destroy', $member) }}" method="POST" data-confirm="Supprimer ce membre ?" data-confirm-ok="Supprimer" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="appbar-btn-white" type="submit" title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Informations du membre -->
	<div class="row g-4 mb-4">
		<div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
						<i class="bi bi-person-lines-fill me-2"></i>
						Informations personnelles
					</h3>
				</div>
                <div class="card-body">
					<div class="row g-4">
						<div class="col-md-6">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-envelope me-2"></i>Email
                                </label>
								<div class="info-value">{{ $member->email ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-telephone me-2"></i>Téléphone
                                </label>
								<div class="info-value">{{ $member->phone ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-gender-ambiguous me-2"></i>Genre
                                </label>
								<div class="info-value">{{ ucfirst($member->gender ?? 'Non renseigné') }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-heart me-2"></i>Statut marital
                                </label>
								<div class="info-value">{{ ucfirst($member->marital_status ?? 'Non renseigné') }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-calendar-date me-2"></i>Date de naissance
                                </label>
								<div class="info-value">{{ optional($member->birth_date)->format('d/m/Y') ?? 'Non renseigné' }}</div>
							</div>
						</div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="bi bi-briefcase me-2"></i>Fonction
                                </label>
                                <div class="info-value">{{ $member->function ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label">
                                    <i class="bi bi-building me-2"></i>Domaine d'Activité
                                </label>
                                <div class="info-value">{{ $member->activity_domain ?? 'Non renseigné' }}</div>
                            </div>
                        </div>
						<div class="col-12">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-geo-alt me-2"></i>Adresse
                                </label>
								<div class="info-value">{{ $member->address ?? 'Non renseigné' }}</div>
							</div>
						</div>
						@if($member->notes)
						<div class="col-12">
							<div class="info-item">
                                <label class="info-label">
									<i class="bi bi-sticky me-2"></i>Notes
                                </label>
								<div class="info-value">{{ $member->notes }}</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
						<i class="bi bi-graph-up me-2"></i>
						Statistiques dîmes
					</h3>
				</div>
                <div class="card-body">
				@php
					$allTithes = $member->tithes()->get();
					$totalTithes = (float) $allTithes->sum('amount');
					$lastTithe = optional($allTithes->sortByDesc('paid_at')->first());
						$thisMonthTithes = $allTithes->where('paid_at', '>=', now()->startOfMonth())->sum('amount');
				@endphp
					<div class="stat-item">
						<div class="stat-icon">
							<i class="bi bi-cash-stack"></i>
						</div>
						<div class="stat-info">
							<div class="stat-label">Total dîmes</div>
							<div class="stat-value" style="color: #000000;">{{ number_format(round($totalTithes), 0, ',', ' ') }} FCFA</div>
						</div>
					</div>
					<div class="stat-item">
						<div class="stat-icon">
							<i class="bi bi-calendar-month"></i>
						</div>
						<div class="stat-info">
							<div class="stat-label">Ce mois</div>
							<div class="stat-value" style="color: #000000;">{{ number_format(round($thisMonthTithes), 0, ',', ' ') }} FCFA</div>
						</div>
					</div>
					<div class="stat-item">
						<div class="stat-icon">
							<i class="bi bi-clock-history"></i>
						</div>
						<div class="stat-info">
							<div class="stat-label">Dernière dîme</div>
							<div class="stat-value">{{ $lastTithe?->paid_at?->format('d/m/Y') ?? 'Aucune' }}</div>
						</div>
					</div>
					<div class="stat-item">
						<div class="stat-icon">
							<i class="bi bi-list-check"></i>
						</div>
						<div class="stat-info">
							<div class="stat-label">Nombre de dîmes</div>
							<div class="stat-value">{{ $allTithes->count() }}</div>
						</div>
				</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Section Remarques -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
				<i class="bi bi-chat-square-text me-2"></i>
				Remarques disciplinaires
			</h3>
            <button class="appbar-btn-yellow" data-bs-toggle="modal" data-bs-target="#addRemarkModal">
                <i class="bi bi-plus-circle"></i>
                <span class="btn-text">Ajouter une remarque</span>
            </button>
		</div>
        <div class="card-body">
            <div id="remarks-container">
			@forelse($member->getFormattedRemarks() as $index => $remark)
                <div class="remark-item border rounded p-3 mb-3" data-index="{{ $index }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="mb-2">{{ $remark['remark'] }}</p>
                            <small class="text-muted">
							<i class="bi bi-calendar3 me-1"></i>{{ $remark['added_at'] }}
                                <span class="mx-2">•</span>
							<i class="bi bi-person me-1"></i>{{ $remark['added_by'] }}
                            </small>
					</div>
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeRemark({{ $index }})" title="Supprimer cette remarque">
					<i class="bi bi-trash"></i>
				</button>
                    </div>
			</div>
			@empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-chat-square-text display-4 opacity-50"></i>
                    <h4 class="mt-3 mb-2">Aucune remarque</h4>
                    <p class="mb-3">Ce membre n'a pas encore de remarques disciplinaires.</p>
                
			</div>
			@endforelse
            </div>
		</div>
	</div>

	<!-- Graphique des dîmes sur l'année -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
						<i class="bi bi-graph-up me-2"></i>
						Évolution des dîmes ({{ $chart['year'] ?? now()->year }})
					</h3>
					</div>
        <div class="card-body">
				<div style="height: 300px;">
					<canvas id="memberTithesChart"></canvas>
			</div>
		</div>
	</div>

	<!-- Historique des dîmes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
				<i class="bi bi-cash-coin me-2"></i>
				Historique des dîmes
			</h3>
            <a class="appbar-btn-yellow" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}">
                <i class="bi bi-plus-circle"></i>
                <span class="btn-text">Ajouter une dîme</span>
            </a>
		</div>
        <div class="card-body">
			@forelse($member->tithes()->latest('paid_at')->get() as $tithe)
            <div class="tithe-item border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
				<div class="tithe-info">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                            <span class="fw-medium">{{ optional($tithe->paid_at)->format('d/m/Y') }}</span>
					</div>
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-credit-card me-2 text-muted"></i>
                            <span class="text-muted">{{ $tithe->payment_method ?? 'Non spécifié' }}</span>
					</div>
					@if($tithe->reference)
                        <div class="d-flex align-items-center">
                            <i class="bi bi-hash me-2 text-muted"></i>
                            <span class="text-muted">{{ $tithe->reference }}</span>
					</div>
					@endif
				</div>
				<div class="tithe-amount">
                        <span class="fw-bold" style="color: #000000;">{{ number_format(round($tithe->amount), 0, ',', ' ') }} FCFA</span>
                    </div>
				</div>
			</div>
					@empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-cash-coin display-4 opacity-50"></i>
                <h4 class="mt-3 mb-2">Aucune dîme</h4>
                <p class="mb-3">Ce membre n'a pas encore enregistré de dîmes.</p>
                <a class="appbar-btn-yellow" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}">
                    <i class="bi bi-plus-circle"></i>
                    <span class="btn-text">Enregistrer la première dîme</span>
                </a>
			</div>
					@endforelse
		</div>
	</div>
</div>

<!-- Modal pour ajouter une remarque -->
<div class="modal fade" id="addRemarkModal" tabindex="-1" aria-labelledby="addRemarkModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
            <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; padding: 20px 24px 16px;">
                <h5 class="modal-title" id="addRemarkModalLabel" style="font-weight: 600; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif;">Ajouter une remarque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.2rem; color: #64748b;"></button>
			</div>
			<form id="addRemarkForm">
                <div class="modal-body" style="padding: 20px 24px;">
					<div class="mb-3">
                        <label for="remarkText" class="form-label" style="font-weight: 600; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.8rem;">Remarque</label>
                        <textarea class="form-control" id="remarkText" name="remark" rows="4" placeholder="Décrivez la remarque disciplinaire ou l'observation..." required maxlength="500" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 16px; font-size: 0.9rem;"></textarea>
                        <div class="form-text" style="color: #64748b; font-size: 0.8rem;">Maximum 500 caractères</div>
					</div>
				</div>
                <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px 20px; gap: 12px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500; padding: 8px 16px; border: 1px solid #e2e8f0; color: #64748b; background: transparent;">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 700; padding: 8px 16px; background-color: #000000; border: 1px solid #000000; color: #ffffff;">
                        <i class="bi bi-plus me-1" style="color: #ffffff;"></i>Ajouter la remarque
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	const addRemarkForm = document.getElementById('addRemarkForm');
	const remarksContainer = document.getElementById('remarks-container');
	
	addRemarkForm.addEventListener('submit', function(e) {
		e.preventDefault();
		
		const formData = new FormData(this);
		const remarkText = formData.get('remark');
		
		if (!remarkText.trim()) {
			alert('Veuillez saisir une remarque');
			return;
		}
		
		// Afficher un indicateur de chargement
		const submitBtn = this.querySelector('button[type="submit"]');
		const originalText = submitBtn.innerHTML;
		submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Ajout en cours...';
		submitBtn.disabled = true;
		
		fetch('{{ route("members.remarks.store", $member) }}', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			},
			body: JSON.stringify({
				remark: remarkText
			})
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				// Fermer le modal
				const modal = bootstrap.Modal.getInstance(document.getElementById('addRemarkModal'));
				modal.hide();
				
				// Réinitialiser le formulaire
				this.reset();
				
				// Recharger les remarques
				loadRemarks();
				
				// Afficher un message de succès
				showAlert('success', data.message);
			} else {
				showAlert('danger', 'Erreur lors de l\'ajout de la remarque');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			showAlert('danger', 'Erreur lors de l\'ajout de la remarque');
		})
		.finally(() => {
			submitBtn.innerHTML = originalText;
			submitBtn.disabled = false;
		});
	});
});

function removeRemark(index) {
	if (!confirm('Êtes-vous sûr de vouloir supprimer cette remarque ?')) {
		return;
	}
	
	fetch(`{{ route("members.remarks.destroy", [$member, 0]) }}`.replace('0', index), {
		method: 'DELETE',
		headers: {
			'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.success) {
			loadRemarks();
			showAlert('success', data.message);
		} else {
			showAlert('danger', 'Erreur lors de la suppression de la remarque');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		showAlert('danger', 'Erreur lors de la suppression de la remarque');
	});
}

function loadRemarks() {
	fetch('{{ route("members.remarks.index", $member) }}')
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				updateRemarksDisplay(data.remarks);
			}
		})
		.catch(error => {
			console.error('Error loading remarks:', error);
		});
}

function updateRemarksDisplay(remarks) {
	const container = document.getElementById('remarks-container');
	
	if (remarks.length === 0) {
		container.innerHTML = `
			<div class="text-center text-muted py-3">
				<i class="bi bi-chat-square-text display-4 opacity-50"></i>
				<p class="mt-2 mb-0">Aucune remarque pour ce membre</p>
			</div>
		`;
		return;
	}
	
	container.innerHTML = remarks.map((remark, index) => `
		<div class="remark-item border rounded p-3 mb-2" data-index="${index}">
			<div class="d-flex justify-content-between align-items-start">
				<div class="flex-grow-1">
					<p class="mb-1">${remark.remark}</p>
					<small class="text-muted">
						<i class="bi bi-calendar me-1"></i>${remark.added_at}
						<span class="mx-2">•</span>
						<i class="bi bi-person me-1"></i>${remark.added_by}
					</small>
				</div>
				<button class="btn btn-sm btn-outline-danger ms-2" onclick="removeRemark(${index})">
					<i class="bi bi-trash"></i>
				</button>
			</div>
		</div>
	`).join('');
}

function showAlert(type, message) {
	const alertDiv = document.createElement('div');
	alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
	alertDiv.innerHTML = `
		${message}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	`;
	
	// Insérer l'alerte en haut de la page
	const container = document.querySelector('.container');
	container.insertBefore(alertDiv, container.firstChild);
	
	// Supprimer automatiquement après 5 secondes
	setTimeout(() => {
		if (alertDiv.parentNode) {
			alertDiv.remove();
		}
	}, 5000);
}

// Graphique des dîmes du membre - Version simple comme dans tithes/index
document.addEventListener('DOMContentLoaded', function(){
    const el = document.getElementById('memberTithesChart');
    if (!el) return;

    const labels = @json($chart['labels_numeric'] ?? range(1,12));
    const raw = @json($chart['data'] ?? []);
    const data = Array.from({ length: 12 }, (_, i) => Number(raw[i] ?? 0));

    const ctx = el.getContext('2d');
    const h = 300;
    const gradient = ctx.createLinearGradient(0, 0, 0, h);
    gradient.addColorStop(0, 'rgba(255,38,0,0.3)');
    gradient.addColorStop(1, 'rgba(255,38,0,0.05)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Dîmes (FCFA)',
                data,
                borderColor: '#FFCC00',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#FFCC00',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: '500'
                        }
                    }
                },
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
                    ticks: { 
                        color: '#6B7280',
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                        }
                    }
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
