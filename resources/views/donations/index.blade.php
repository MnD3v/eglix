@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-heart me-3"></i>
                    Dons
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-gift me-2"></i>
                    Gérez les dons des membres et bienfaiteurs
                </p>
            </div>
            <div>
                <a href="{{ route('donations.create') }}" class="btn-add">
                    <i class="bi bi-plus-lg" style="color: white !important;"></i>
                    <span class="btn-text">Nouveau don</span>
                </a>
            </div>
        </div>
    </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($donations as $donation)
            <div class="col">
                <div class="card card-soft h-100 position-relative">
                    <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px; background-color: {{ $donation->donation_type === 'money' ? '#FF2600' : '#22C55E' }}20; border: 2px solid {{ $donation->donation_type === 'money' ? '#FF2600' : '#22C55E' }};">
                                        <i class="bi {{ $donation->donation_type === 'money' ? 'bi-cash-coin' : 'bi-box' }}" 
                                           style="color: {{ $donation->donation_type === 'money' ? '#FF2600' : '#22C55E' }};"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-0 fw-semibold">
                                            {{ $donation->donor_name ?? ($donation->member?->last_name.' '.$donation->member?->first_name) }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ optional($donation->received_at)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                                
                                @if($donation->donation_type === 'money')
                                    <div class="mb-2 d-flex align-items-center justify-content-between">
                                        <span class="badge bg-custom">Argent</span>
                                        <span class="fw-bold numeric">{{ number_format(round($donation->amount), 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    @if($donation->payment_method)
                                        <div class="small text-muted mb-1">
                                            <i class="bi bi-credit-card me-1"></i>{{ ucfirst($donation->payment_method) }}
                                        </div>
                                    @endif
                                @else
                                    <div class="mb-2 d-flex align-items-center justify-content-between">
                                        <span class="badge bg-success">Objet physique</span>
                                        <span class="fw-semibold">{{ $donation->physical_item }}</span>
                                    </div>
                                    @if($donation->physical_description)
                                        <div class="small text-muted mb-1">{{ Str::limit($donation->physical_description, 60) }}</div>
                                    @endif
                                @endif

                                @if($donation->project)
                                    <div class="small text-muted mb-1"><i class="bi bi-kanban me-1"></i>{{ $donation->project->name }}</div>
                                @elseif($donation->title)
                                    <div class="small text-muted mb-1"><i class="bi bi-tag me-1"></i>{{ $donation->title }}</div>
                                @else
                                    <div class="small text-muted mb-1"><i class="bi bi-gift me-1"></i>Don général</div>
                                @endif

                                @if($donation->reference)
                                    <div class="small text-muted mb-1"><i class="bi bi-hash me-1"></i>{{ $donation->reference }}</div>
                                @endif

                                @if($donation->notes)
                                    <div class="small text-muted"><i class="bi bi-chat-text me-1"></i>{{ Str::limit($donation->notes, 50) }}</div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $donation->created_at->format('d/m/Y') }}</small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('donations.show', $donation) }}" class="btn btn-outline-primary btn-sm" title="Voir le détail"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('donations.edit', $donation) }}" class="btn btn-outline-secondary btn-sm" title="Modifier"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce don ?')" title="Supprimer"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-heart display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucun don</h4>
                    <p class="text-muted">Commencez par enregistrer votre premier don.</p>
                    <a href="{{ route('donations.create') }}" class="btn-add-empty">
                        <i class="bi bi-plus-circle" style="color: white !important;"></i>
                        Créer un don
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($donations->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $donations->links() }}
        </div>
    @endif
</div>
@endsection


