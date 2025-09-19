@extends('layouts.app')
@section('content')

<style>
/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6B7280;
    font-size: 1rem;
    margin-bottom: 0;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-bottom: 2rem;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn.primary {
    background: #8B5CF6;
    color: white;
    border-color: #8B5CF6;
}

.action-btn.primary:hover {
    background: #7C3AED;
    border-color: #7C3AED;
    color: white;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    padding: 0.25rem;
    background: #F9FAFB;
    border-radius: 8px;
    width: fit-content;
}

.filter-tab {
    padding: 0.5rem 1rem;
    border: none;
    background: transparent;
    color: #6B7280;
    font-weight: 500;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.filter-tab.active {
    background: #8B5CF6;
    color: white;
}

.filter-tab:hover:not(.active) {
    background: #E5E7EB;
    color: #374151;
}

/* Event Cards */
.event-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    position: relative;
}

.event-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.event-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.event-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.event-icon.conference { background: #8B5CF6; }
.event-icon.celebration { background: #EC4899; }
.event-icon.meeting { background: #22C55E; }
.event-icon.other { background: #6B7280; }

.event-info {
    flex: 1;
    min-width: 0;
}

.event-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.event-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.4;
}

.event-meta {
    margin-bottom: 1rem;
}

.event-date {
    background: #8B5CF6;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.event-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6B7280;
}

.event-detail {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.event-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.action-btn-small {
    width: 32px;
    height: 32px;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.action-btn-small:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn-small.danger:hover {
    border-color: #EF4444;
    background: #FEF2F2;
    color: #EF4444;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6B7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .filter-tabs {
        width: 100%;
        justify-content: center;
    }
    
    .event-header {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .event-actions {
        justify-content: flex-start;
    }
}
</style>

<div class="container py-4">
    <!-- AppBar Événements -->
    <div class="appbar events-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Événements</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-calendar-check appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">Gérez les événements et activités de l'église</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('events.create') }}" class="appbar-btn-primary">
                    <i class="bi bi-plus"></i>
                    <span>Nouvel événement</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">Tous</button>
        <button class="filter-tab" data-filter="conference">Conférences</button>
        <button class="filter-tab" data-filter="celebration">Célébrations</button>
        <button class="filter-tab" data-filter="meeting">Réunions</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($events->count() > 0)
        <div class="row g-3">
            @foreach($events as $event)
            <div class="col-lg-6" data-event-type="{{ strtolower($event->type ?? 'other') }}">
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-icon {{ strtolower($event->type ?? 'other') }}">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="event-info">
                            <h3 class="event-title">{{ $event->title }}</h3>
                            <p class="event-description">{{ $event->description ?? 'Aucune description' }}</p>
                        </div>
                    </div>
                    <div class="event-meta">
                        <div class="event-date">
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}
                        </div>
                        <div class="event-details">
                            @if($event->type)
                                <div class="event-detail">
                                    <i class="bi bi-tag"></i>
                                    <span>{{ $event->type }}</span>
                                </div>
                            @endif
                            @if($event->location)
                                <div class="event-detail">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>
    </div>
                    <div class="event-actions">
                        <a href="{{ route('events.show', $event) }}" class="action-btn-small" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('events.edit', $event) }}" class="action-btn-small" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet événement ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn-small danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
    </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
    {{ $events->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <h3>Aucun événement</h3>
            <p>Aucun événement n'a été créé pour le moment.</p>
            <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus me-2"></i>
                Créer le premier événement
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets de filtrage
    const filterTabs = document.querySelectorAll('.filter-tab');
    const eventCards = document.querySelectorAll('[data-event-type]');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide event cards
            eventCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    card.style.display = card.getAttribute('data-event-type') === filter ? 'block' : 'none';
                }
            });
        });
    });
});
</script>
@endsection