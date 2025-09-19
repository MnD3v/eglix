@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('partials.back-button')
	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<!-- Profile Header -->
	<div class="member-profile-header fade-in">
		<div class="profile-background"></div>
		<div class="profile-content">
			<div class="profile-main">
				<div class="profile-info-section">
					@php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
					<div class="profile-avatar">
						{{ $initials }}
					</div>
					<div class="profile-info">
						<h1 class="profile-name">{{ $member->last_name }} {{ $member->first_name }}</h1>
						<div class="profile-meta">
							<span class="status-badge status-{{ $member->status }}">
								<i class="bi bi-circle-fill me-1"></i>{{ ucfirst($member->status) }}
							</span>
							<span class="meta-item">
								<i class="bi bi-calendar-event me-1"></i>Rejoint le {{ optional($member->joined_at)->format('d/m/Y') ?: '—' }}
							</span>
							<span class="meta-item">
								<i class="bi bi-droplet me-1"></i>Baptême: {{ optional($member->baptized_at)->format('d/m/Y') ?: '—' }}
							</span>
						</div>
					</div>
				</div>
				<div class="profile-actions">
					<a class="btn btn-outline-light" href="{{ route('members.edit', $member) }}">
						<i class="bi bi-pencil me-2"></i>Modifier
					</a>
					<a class="btn btn-primary" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}">
						<i class="bi bi-cash-coin me-2"></i>Enregistrer une dîme
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Info Grid -->
	<div class="row g-4 mb-4">
		<div class="col-lg-8">
			<div class="info-card slide-in-left">
				<div class="info-header">
					<h3 class="info-title">
						<i class="bi bi-person-lines-fill me-2"></i>
						Informations personnelles
					</h3>
				</div>
				<div class="info-content">
					<div class="row g-4">
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-envelope me-2"></i>Email
								</div>
								<div class="info-value">{{ $member->email ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-telephone me-2"></i>Téléphone
								</div>
								<div class="info-value">{{ $member->phone ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-gender-ambiguous me-2"></i>Genre
								</div>
								<div class="info-value">{{ ucfirst($member->gender ?? 'Non renseigné') }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-heart me-2"></i>Statut marital
								</div>
								<div class="info-value">{{ ucfirst($member->marital_status ?? 'Non renseigné') }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-calendar-date me-2"></i>Date de naissance
								</div>
								<div class="info-value">{{ optional($member->birth_date)->format('d/m/Y') ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-briefcase me-2"></i>Fonction
								</div>
								<div class="info-value">{{ $member->function ?? 'Non renseigné' }}</div>
							</div>
						</div>
						<div class="col-12">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-geo-alt me-2"></i>Adresse
								</div>
								<div class="info-value">{{ $member->address ?? 'Non renseigné' }}</div>
							</div>
						</div>
						@if($member->notes)
						<div class="col-12">
							<div class="info-item">
								<div class="info-label">
									<i class="bi bi-sticky me-2"></i>Notes
								</div>
								<div class="info-value">{{ $member->notes }}</div>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="stats-card slide-in-right">
				<div class="stats-header">
					<h3 class="stats-title">
						<i class="bi bi-graph-up me-2"></i>
						Statistiques dîmes
					</h3>
				</div>
				<div class="stats-content">
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
							<div class="stat-value">{{ number_format(round($totalTithes), 0, ',', ' ') }} FCFA</div>
						</div>
					</div>
					<div class="stat-item">
						<div class="stat-icon">
							<i class="bi bi-calendar-month"></i>
						</div>
						<div class="stat-info">
							<div class="stat-label">Ce mois</div>
							<div class="stat-value">{{ number_format(round($thisMonthTithes), 0, ',', ' ') }} FCFA</div>
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
	<div class="remarks-section scale-in">
		<div class="remarks-header">
			<h3 class="remarks-title">
				<i class="bi bi-chat-square-text me-2"></i>
				Remarques disciplinaires
			</h3>
			<button class="btn btn-primary btn-add-remark" data-bs-toggle="modal" data-bs-target="#addRemarkModal">
				<i class="bi bi-plus-circle me-2"></i>Ajouter une remarque
			</button>
		</div>
		
		<div id="remarks-container" class="remarks-list">
			@forelse($member->getFormattedRemarks() as $index => $remark)
			<div class="remark-item" data-index="{{ $index }}">
				<div class="remark-content">
					<div class="remark-text">{{ $remark['remark'] }}</div>
					<div class="remark-meta">
						<span class="remark-date">
							<i class="bi bi-calendar3 me-1"></i>{{ $remark['added_at'] }}
						</span>
						<span class="remark-author">
							<i class="bi bi-person me-1"></i>{{ $remark['added_by'] }}
						</span>
					</div>
				</div>
				<button class="btn btn-remove-remark" onclick="removeRemark({{ $index }})" title="Supprimer cette remarque">
					<i class="bi bi-trash"></i>
				</button>
			</div>
			@empty
			<div class="remarks-empty">
				<div class="empty-icon">
					<i class="bi bi-chat-square-text"></i>
				</div>
				<h4 class="empty-title">Aucune remarque</h4>
				<p class="empty-description">Ce membre n'a pas encore de remarques disciplinaires.</p>
				<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addRemarkModal">
					<i class="bi bi-plus-circle me-2"></i>Ajouter la première remarque
				</button>
			</div>
			@endforelse
		</div>
	</div>

	<!-- Historique des dîmes -->
	<div class="tithes-section animate-on-scroll">
		<div class="tithes-header">
			<h3 class="tithes-title">
				<i class="bi bi-cash-coin me-2"></i>
				Historique des dîmes
			</h3>
			<a class="btn btn-primary btn-add-tithe" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}">
				<i class="bi bi-plus-circle me-2"></i>Ajouter une dîme
			</a>
		</div>
		
		<div class="tithes-content">
			@forelse($member->tithes()->latest('paid_at')->get() as $tithe)
			<div class="tithe-item">
				<div class="tithe-info">
					<div class="tithe-date">
						<i class="bi bi-calendar3 me-2"></i>
						{{ optional($tithe->paid_at)->format('d/m/Y') }}
					</div>
					<div class="tithe-method">
						<i class="bi bi-credit-card me-2"></i>
						{{ $tithe->payment_method ?? 'Non spécifié' }}
					</div>
					@if($tithe->reference)
					<div class="tithe-reference">
						<i class="bi bi-hash me-2"></i>
						{{ $tithe->reference }}
					</div>
					@endif
				</div>
				<div class="tithe-amount">
					{{ number_format(round($tithe->amount), 0, ',', ' ') }} FCFA
				</div>
			</div>
					@empty
			<div class="tithes-empty">
				<div class="empty-icon">
					<i class="bi bi-cash-coin"></i>
				</div>
				<h4 class="empty-title">Aucune dîme</h4>
				<p class="empty-description">Ce membre n'a pas encore enregistré de dîmes.</p>
				<a class="btn btn-primary" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}">
					<i class="bi bi-plus-circle me-2"></i>Enregistrer la première dîme
				</a>
			</div>
					@endforelse
		</div>
	</div>
</div>

<!-- Modal pour ajouter une remarque -->
<div class="modal fade" id="addRemarkModal" tabindex="-1" aria-labelledby="addRemarkModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addRemarkModalLabel">Ajouter une remarque</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="addRemarkForm">
				<div class="modal-body">
					<div class="mb-3">
						<label for="remarkText" class="form-label">Remarque</label>
						<textarea class="form-control" id="remarkText" name="remark" rows="4" placeholder="Décrivez la remarque disciplinaire ou l'observation..." required maxlength="500"></textarea>
						<div class="form-text">Maximum 500 caractères</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
					<button type="submit" class="btn btn-primary">
						<i class="bi bi-plus me-1"></i>Ajouter la remarque
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@push('scripts')
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
</script>
@endpush
