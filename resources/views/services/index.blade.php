@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-music-note-beamed me-3"></i>
                    Cultes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-calendar-check me-2"></i>
                    Gérez les cultes et services de l'église
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('service-roles.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-badge me-2"></i>
                    <span class="btn-label">Rôles</span>
                </a>
                <a href="{{ route('services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span class="btn-label">Nouveau culte</span>
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    
    <!-- Services Cards Grid -->
    <div class="row g-4">
        @forelse($services as $s)
        <div class="col-lg-4 col-md-6">
            <div class="service-card">
                <div class="service-card-header">
                    <div class="service-date">
                        <div class="service-day">{{ \Carbon\Carbon::parse($s->date)->format('d') }}</div>
                        <div class="service-month">{{ \Carbon\Carbon::parse($s->date)->format('M') }}</div>
                        <div class="service-year">{{ \Carbon\Carbon::parse($s->date)->format('Y') }}</div>
                    </div>
                    <div class="service-info">
                        <h3 class="service-title">{{ $s->theme ?? 'Culte dominical' }}</h3>
                        @if($s->type)
                            <span class="service-type">{{ $s->type }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="service-card-body">
                    @if($s->preacher)
                    <div class="service-detail">
                        <i class="bi bi-person-circle text-primary"></i>
                        <span class="service-label">Prédicateur :</span>
                        <span class="service-value">{{ $s->preacher }}</span>
                    </div>
                    @endif
                    
                    @if($s->choir)
                    <div class="service-detail">
                        <i class="bi bi-music-note-beamed text-success"></i>
                        <span class="service-label">Chœur :</span>
                        <span class="service-value">{{ $s->choir }}</span>
                    </div>
                    @endif
                    
                    @if($s->location)
                    <div class="service-detail">
                        <i class="bi bi-geo-alt text-warning"></i>
                        <span class="service-label">Lieu :</span>
                        <span class="service-value">{{ $s->location }}</span>
                    </div>
                    @endif
                    
                    @if($s->start_time)
                    <div class="service-detail">
                        <i class="bi bi-clock text-info"></i>
                        <span class="service-label">Heure :</span>
                        <span class="service-value">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }}</span>
                    </div>
                    @endif
                    
                    @php
                        $assignmentsCount = $s->assignments()->count();
                    @endphp
                    <div class="service-detail">
                        <i class="bi bi-person-badge text-secondary"></i>
                        <span class="service-label">Rôles assignés :</span>
                        <span class="service-value">
                            @if($assignmentsCount > 0)
                                <span class="badge bg-success">{{ $assignmentsCount }} rôle{{ $assignmentsCount > 1 ? 's' : '' }}</span>
                            @else
                                <span class="text-muted">Aucun</span>
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="service-card-footer">
                    <div class="service-actions">
                        <a href="{{ route('services.program', $s) }}" class="btn btn-primary btn-sm" title="Programmer les rôles">
                            <i class="bi bi-person-badge me-1"></i>
                            Programmer
                        </a>
                        <a href="{{ route('services.edit', $s) }}" class="btn btn-outline-secondary btn-sm" title="Modifier le culte">
                            <i class="bi bi-pencil me-1"></i>
                            Modifier
                        </a>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ route('services.destroy', $s) }}', 'ce culte')" title="Supprimer le culte">
                            <i class="bi bi-trash me-1"></i>
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h3 class="empty-state-title">Aucun culte planifié</h3>
                <p class="empty-state-description">Commencez par créer votre premier culte pour organiser les services de l'église.</p>
                <a href="{{ route('services.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    Créer le premier culte
                </a>
            </div>
        </div>
        @endforelse
    </div>
    {{ $services->links() }}
</div>

<style>
/* Styles pour les cartes de cultes */
.service-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    border-color: #dee2e6;
}

.service-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.service-date {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 12px;
    text-align: center;
    min-width: 70px;
    backdrop-filter: blur(10px);
}

.service-day {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}

.service-month {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    opacity: 0.9;
}

.service-year {
    font-size: 10px;
    opacity: 0.8;
}

.service-info {
    flex: 1;
}

.service-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: white;
}

.service-type {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.service-card-body {
    padding: 20px;
    flex: 1;
}

.service-detail {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 14px;
}

.service-detail:last-child {
    margin-bottom: 0;
}

.service-detail i {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.service-label {
    font-weight: 500;
    color: #6c757d;
    min-width: 100px;
}

.service-value {
    color: #495057;
    font-weight: 400;
}

.service-card-footer {
    padding: 16px 20px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.service-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.service-actions .btn {
    flex: 1;
    min-width: 80px;
    font-size: 12px;
    padding: 6px 12px;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 16px;
    border: 2px dashed #dee2e6;
}

.empty-state-icon {
    font-size: 64px;
    color: #6c757d;
    margin-bottom: 20px;
}

.empty-state-title {
    font-size: 24px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 12px;
}

.empty-state-description {
    color: #6c757d;
    font-size: 16px;
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .service-card-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .service-date {
        min-width: 60px;
    }
    
    .service-day {
        font-size: 20px;
    }
    
    .service-title {
        font-size: 16px;
    }
    
    .service-actions {
        flex-direction: column;
    }
    
    .service-actions .btn {
        flex: none;
    }
}

/* Animation d'apparition */
.service-card {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Fonction de confirmation de suppression
function confirmDelete(url, itemName) {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const modalMessage = document.getElementById('confirmModalMessage');
    const modalOk = document.getElementById('confirmModalOk');
    
    // Personnaliser le modal pour la suppression
    modalMessage.innerHTML = `
        <div class="text-center">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem; margin-bottom: 1rem;"></i>
            <p class="mb-0">Êtes-vous sûr de vouloir supprimer ${itemName} ?</p>
            <small class="text-muted">Cette action est irréversible.</small>
        </div>
    `;
    
    modalOk.innerHTML = '<i class="bi bi-trash me-2"></i>Supprimer';
    modalOk.className = 'btn btn-danger';
    
    // Supprimer les anciens événements
    modalOk.replaceWith(modalOk.cloneNode(true));
    const newModalOk = document.getElementById('confirmModalOk');
    
    // Ajouter le nouvel événement
    newModalOk.addEventListener('click', function() {
        // Créer un formulaire temporaire pour la suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    });
    
    modal.show();
}
</script>
@endsection


