@extends('layouts.app')

@section('content')
<style>
.guest-profile-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.guest-profile-header {
    display: flex;
    align-items: flex-start;
    gap: 2rem;
    margin-bottom: 2rem;
}

.guest-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FFCC00, #f59e0b);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    font-weight: 700;
}

.guest-profile-info {
    flex: 1;
}

.guest-name {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.guest-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.guest-status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-visit-1 { background: #dbeafe; color: #1e40af; }
.status-visit-2 { background: #fef3c7; color: #92400e; }
.status-returning { background: #dcfce7; color: #166534; }
.status-converted { background: #fce7f3; color: #be185d; }
.status-interested { background: #fee2e2; color: #dc2626; }

.guest-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.detail-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
}

.detail-value {
    font-size: 0.875rem;
    color: #1e293b;
}

.guest-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.guest-actions .btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.guest-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.guest-actions .btn i {
    color: #000000 !important;
}

@media (max-width: 768px) {
    .guest-profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .guest-meta {
        justify-content: center;
    }
    
    .guest-actions {
        flex-direction: column;
    }
}
</style>

<div class="container py-4">
    <!-- AppBar Détails Invité -->
    <div class="appbar guests-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('guests.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">{{ $guest->full_name }}</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('guests.edit', $guest) }}" class="appbar-btn-yellow">
                    <i class="bi bi-pencil"></i>
                    <span class="btn-text">Modifier</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Profil de l'invité -->
    <div class="guest-profile-card">
        <div class="guest-profile-header">
            <div class="guest-avatar">
                {{ strtoupper(substr($guest->first_name, 0, 1)) }}{{ strtoupper(substr($guest->last_name, 0, 1)) }}
            </div>
            <div class="guest-profile-info">
                <h2 class="guest-name">{{ $guest->full_name }}</h2>
                <div class="guest-meta">
                    <span class="guest-status-badge status-{{ str_replace('_', '-', $guest->status) }}">
                        {{ $guest->status_label }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar-event me-1"></i>
                        Visité le {{ $guest->visit_date->format('d/m/Y') }}
                    </span>
                </div>
                @if($guest->phone || $guest->email)
                    <div class="row">
                        @if($guest->phone)
                            <div class="col-md-6">
                                <i class="bi bi-telephone me-2"></i>
                                <strong>{{ $guest->phone }}</strong>
                            </div>
                        @endif
                        @if($guest->email)
                            <div class="col-md-6">
                                <i class="bi bi-envelope me-2"></i>
                                <strong>{{ $guest->email }}</strong>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails de l'invité -->
        <div class="guest-details-grid">
            <!-- Informations de contact -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i all="bi bi-person"></i>
                    Informations Personnelles
                </h3>
                @if($guest->address)
                    <div class="detail-item">
                        <span class="detail-label">Adresse</span>
                        <span class="detail-value">{{ $guest->address }}</span>
                    </div>
                @endif
                @if($guest->phone)
                    <div class="detail-item">
                        <span class="detail-label">Téléphone</span>
                        <span class="detail-value">{{ $guest->phone }}</span>
                    </div>
                @endif
                @if($guest->email)
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $guest->email }}</span>
                    </div>
                @endif
            </div>

            <!-- Informations de visite -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i aria="bi bi-calendar-check"></i>
                    Informations de Visite
                </h3>
                <div class="detail-item">
                    <span class="detail-label">Date de visite</span>
                    <span class="detail-value">{{ $guest->visit_date->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Origine</span>
                    <span class="detail-value">{{ $guest->origin_label }}</span>
                </div>
                @if($guest->referral_source)
                    <div class="detail-item">
                        <span class="detail-label">Source détaillée</span>
                        <span class="detail-value">{{ $guest->referral_source }}</span>
                    </div>
                @endif
                @if($guest->welcomedBy)
                    <div class="detail-item">
                        <span class="detail-label">Accueilli par</span>
                        <span class="detail-value">{{ $guest->welcomedBy->name }}</span>
                    </div>
                @endif
            </div>

            <!-- Informations spirituelles -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-heart"></i>
                    Informations Spirituelles
                </h3>
                @if($guest->church_background)
                    <div class="detail-item">
                        <span class="detail-label">Église d'origine</span>
                        <span class="detail-value">{{ $guest->church_background }}</span>
                    </div>
                @endif
                @if($guest->spiritual_status)
                    <div class="detail-item">
                        <span class="detail-label">Statut spirituel</span>
                        <span class="detail-value">{{ $guest->spiritual_status }}</span>
                    </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Statut actuel</span>
                    <span class="detail-value">{{ $guest->status_label }}</span>
                </div>
            </div>

            <!-- Notes -->
            <div class="detail-section">
                <h3 class="section-title">
                    <i class="bi bi-journal-text"></i>
                    Notes
                </h3>
                @if($guest->spiritual_notes)
                    <div class="mt-3">
                        <h6 class="mb-2">Notes spirituelles :</h6>
                        <p class="text-muted">{{ $guest->spiritual_notes }}</p>
                    </div>
                @endif
                @if($guest->notes)
                    <div class="mt-3">
                        <h6 class="mb-2">Notes générales :</h6>
                        <p class="text-muted">{{ $guest->notes }}</p>
                    </div>
                @endif
                @if(!$guest->spiritual_notes && !$guest->notes)
                    <p class="text-muted">Aucune note disponible</p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="guest-actions">
            <a href="{{ route('guests.edit', $guest) }}" class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-2"></i>Modifier
            </a>
            @if($guest->status !== 'member_converted')
                <form action="{{ route('guests.convert.to.member', $guest) }}" method="POST" class="d-inline">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-check me-2"></i>Convertir en membre
                    </button>
                </form>
            @endif
            <form action="{{ route('guests.destroy', $guest) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet invité ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
