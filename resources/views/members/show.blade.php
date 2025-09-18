@extends('layouts.app')

@section('content')
<div class="container py-4">
    @include('partials.back-button')
	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<!-- Profile Header -->
	<div class="card card-soft p-3 mb-3">
		<div class="d-flex align-items-center justify-content-between gap-3">
			<div class="d-flex align-items-center gap-3">
				@php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
				<div class="rounded-circle d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#FFE1DB;color:#FF2600;font-weight:800;font-size:1.2rem;">
					{{ $initials }}
				</div>
				<div>
					<div class="d-flex align-items-center gap-2">
						<h1 class="h4 m-0">{{ $member->last_name }} {{ $member->first_name }}</h1>
						<span class="badge bg-{{ $member->status==='active'?'success':'secondary' }} text-uppercase">{{ $member->status }}</span>
					</div>
					<div class="text-muted small mt-1">
						<i class="bi bi-calendar-event me-1"></i>Rejoint le {{ optional($member->joined_at)->format('d/m/Y') ?: '—' }}
						<span class="mx-2">•</span>
						<i class="bi bi-droplet me-1"></i>Baptême: {{ optional($member->baptized_at)->format('d/m/Y') ?: '—' }}
					</div>
				</div>
			</div>
			<div class="d-flex gap-2">
				<a class="btn btn-outline-secondary" href="{{ route('members.edit', $member) }}"><i class="bi bi-pencil me-1"></i>Modifier</a>
				<a class="btn btn-primary" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}"><i class="bi bi-cash-coin me-1"></i>Enregistrer une dîme</a>
			</div>
		</div>
	</div>

	<!-- Info Grid -->
	<div class="row g-3 mb-4">
		<div class="col-lg-7">
			<div class="card card-soft p-3 h-100">
				<h2 class="h6 text-uppercase text-muted mb-3">Informations</h2>
				<div class="row g-3">
					<div class="col-md-6"><div class="small text-muted">Email</div><div class="fw-semibold">{{ $member->email ?? '—' }}</div></div>
					<div class="col-md-6"><div class="small text-muted">Téléphone</div><div class="fw-semibold">{{ $member->phone ?? '—' }}</div></div>
					<div class="col-md-12"><div class="small text-muted">Adresse</div><div class="fw-semibold">{{ $member->address ?? '—' }}</div></div>
					<div class="col-md-12"><div class="small text-muted">Notes</div><div class="fw-semibold">{{ $member->notes ?? '—' }}</div></div>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="card card-soft p-3 h-100">
				<h2 class="h6 text-uppercase text-muted mb-3">Synthèse dîmes</h2>
				@php
					$allTithes = $member->tithes()->get();
					$totalTithes = (float) $allTithes->sum('amount');
					$lastTithe = optional($allTithes->sortByDesc('paid_at')->first());
				@endphp
				<div class="d-flex align-items-center justify-content-between mb-2">
					<div class="text-muted">Total dîmes</div>
					<div class="fw-bold numeric">{{ number_format(round($totalTithes), 0, ',', ' ') }} FCFA</div>
				</div>
				<div class="d-flex align-items-center justify-content-between">
					<div class="text-muted">Dernière dîme</div>
					<div class="fw-semibold">{{ $lastTithe?->paid_at?->format('d/m/Y') ?: '—' }}</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Tithes Table -->
	<div class="card card-soft p-3">
		<div class="d-flex justify-content-between align-items-center mb-2">
			<h2 class="h6 m-0">Historique des dîmes</h2>
			<a class="btn btn-sm btn-outline-primary" href="{{ route('tithes.create', ['member_id'=>$member->id]) }}"><i class="bi bi-plus me-1"></i>Ajouter</a>
		</div>
		<div class="table-responsive">
			<table class="table table-sm align-middle">
				<thead>
					<tr>
						<th class="text-muted fw-normal">Date</th>
						<th class="text-muted fw-normal text-end">Montant</th>
						<th class="text-muted fw-normal">Méthode</th>
						<th class="text-muted fw-normal">Référence</th>
					</tr>
				</thead>
				<tbody>
					@forelse($member->tithes()->latest('paid_at')->get() as $t)
					<tr>
						<td>{{ optional($t->paid_at)->format('d/m/Y') }}</td>
						<td class="text-end numeric">{{ number_format(round($t->amount), 0, ',', ' ') }} FCFA</td>
						<td>{{ $t->payment_method ?? '—' }}</td>
						<td>{{ $t->reference ?? '—' }}</td>
					</tr>
					@empty
					<tr><td colspan="4" class="text-center text-muted">Aucune dîme</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection


