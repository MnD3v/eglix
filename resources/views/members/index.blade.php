@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-people me-3"></i>
                    Membres
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-person-check me-2"></i>
                    Gérez les membres de votre église
                </p>
            </div>
            <div>
                <a href="{{ route('members.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    <span class="btn-label">Nouveau membre</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Statistiques membres -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-info p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">TOTAL</div>
                        <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-success p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">ACTIFS</div>
                        <div class="kpi-value">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-check2-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-warning p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">INACTIFS</div>
                        <div class="kpi-value">{{ $stats['inactive'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-slash-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-purple p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">ENFANTS (<18)</div>
                        <div class="kpi-value">{{ $stats['children'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-emoji-smile"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">HOMMES</div>
                        <div class="kpi-value">{{ $stats['male'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-male"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">FEMMES</div>
                        <div class="kpi-value">{{ $stats['female'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-female"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">AUTRES</div>
                        <div class="kpi-value">{{ $stats['other'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-ambiguous"></i></div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Rechercher un membre (nom, email, téléphone)" name="q" value="{{ $search ?? '' }}">
            @if(!empty($search))
            <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Effacer</a>
            @endif
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($members as $member)
            <div class="col">
                <div class="card card-soft h-100 position-relative">
                    <div class="card-body d-flex gap-3 card-link" data-href="{{ route('members.show', $member) }}" style="cursor: pointer;">
                        <div class="flex-shrink-0">
                            @php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#E0F2FE;color:#0EA5E9;font-weight:700;">{{ $initials }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $member->last_name }} {{ $member->first_name }}</div>
                                    <div class="small text-muted">{{ $member->email ?: '—' }}</div>
                                </div>
                                <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'secondary' }}">{{ $member->status }}</span>
                            </div>
                            <div class="mt-2 small text-muted"><i class="bi bi-telephone me-1"></i>{{ $member->phone ?: '—' }}</div>
                            
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('members.edit', $member) }}">Modifier</a>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTitheModal-{{ $member->id }}">
                                <i class="bi bi-cash-coin me-1"></i>Ajouter dîme
                            </button>
                        </div>
                        <form action="{{ route('members.destroy', $member) }}" method="POST" data-confirm="Supprimer ce membre ?" data-confirm-ok="Supprimer">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Ajouter dîme -->
            <div class="modal fade" id="addTitheModal-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter une dîme — {{ $member->last_name }} {{ $member->first_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('tithes.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{ $member->id }}">
                            <input type="hidden" name="redirect" value="{{ route('members.index', request()->query()) }}">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="paid_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Montant</label>
                                        <input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="0,00" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Moyen de paiement</label>
                                        <input type="text" name="payment_method" class="form-control" placeholder="Espèces, Mobile Money…">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Référence</label>
                                        <input type="text" name="reference" class="form-control" placeholder="#REF">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Optionnel"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="text-center text-muted py-5">Aucun membre</div></div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $members->links() }}
    </div>
</div>
@endsection


