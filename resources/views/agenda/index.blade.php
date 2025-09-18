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
    background: #FF2600;
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

.event-icon.services {
    background: #FF2600;
}

.event-icon.events {
    background: #8B5CF6;
}

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
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.event-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.event-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6B7280;
    font-size: 0.875rem;
}

.event-date i {
    color: #9CA3AF;
}

.event-location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6B7280;
    font-size: 0.875rem;
}

.event-location i {
    color: #9CA3AF;
}

.event-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.action-btn {
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

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

.action-btn.danger:hover {
    border-color: #EF4444;
    background: #FEF2F2;
    color: #EF4444;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
}

.empty-icon {
    width: 64px;
    height: 64px;
    background: #E5E7EB;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: #9CA3AF;
}

.empty-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
}

.empty-action {
    background: #FF2600;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    display: inline-block;
    transition: all 0.2s ease;
}

.empty-action:hover {
    background: #E52200;
    color: white;
    text-decoration: none;
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
    
    .event-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .event-actions {
        justify-content: flex-start;
    }
}
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Agenda Partagé</h1>
        <p class="page-subtitle">Consultez tous les cultes et événements de l'église</p>
        <div class="d-flex align-items-center gap-2 mt-2">
            <i class="bi bi-calendar3 text-muted"></i>
            <span class="text-muted">{{ $from->format('d/m/Y') }} — {{ $to->format('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">Tous</button>
        <button class="filter-tab" data-filter="services">Cultes</button>
        <button class="filter-tab" data-filter="events">Événements</button>
    </div>

    <div class="row g-4">
        <!-- Cultes Section -->
        <div class="col-lg-6" data-section="services">
            @forelse($services as $s)
                <a href="{{ route('services.show', $s) }}" class="text-decoration-none">
                    <div class="event-card">
                        <div class="event-header">
                            <div class="event-icon services">
                                <i class="bi bi-house-door"></i>
                            </div>
                            <div class="event-info">
                                <h3 class="event-title">{{ $s->theme ?? 'Culte' }}</h3>
                                <p class="event-description">Culte dominical de l'église</p>
                            </div>
                        </div>
                        <div class="event-meta">
                            <div class="event-date">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}
                            </div>
                            @if($s->time)
                                <div class="event-date">
                                    <i class="bi bi-clock"></i>
                                    {{ \Carbon\Carbon::parse($s->time)->format('H:i') }}
                                </div>
                            @endif
                            @if($s->location)
                                <div class="event-location">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $s->location }}
                                </div>
                            @endif
                        </div>
                        <div class="event-actions">
                            <a href="{{ route('services.show', $s) }}" class="action-btn" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('services.edit', $s) }}" class="action-btn" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="action-btn danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce culte ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-house-door"></i>
                    </div>
                    <h3 class="empty-title">Aucun culte planifié</h3>
                    <p class="empty-description">Il n'y a pas de culte prévu pour cette période.</p>
                    <a href="{{ route('services.create') }}" class="empty-action">
                        <i class="bi bi-plus-circle me-2"></i>
                        Créer un culte
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Événements Section -->
        <div class="col-lg-6" data-section="events">
            @forelse($events as $e)
                <a href="{{ route('events.show', $e) }}" class="text-decoration-none">
                    <div class="event-card">
                        <div class="event-header">
                            <div class="event-icon events">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="event-info">
                                <h3 class="event-title">{{ $e->title }}</h3>
                                <p class="event-description">{{ $e->description ?? 'Événement de l\'église' }}</p>
                            </div>
                        </div>
                        <div class="event-meta">
                            <div class="event-date">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}
                            </div>
                            @if($e->time)
                                <div class="event-date">
                                    <i class="bi bi-clock"></i>
                                    {{ \Carbon\Carbon::parse($e->time)->format('H:i') }}
                                </div>
                            @endif
                            @if($e->location)
                                <div class="event-location">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $e->location }}
                                </div>
                            @endif
                        </div>
                        <div class="event-actions">
                            <a href="{{ route('events.show', $e) }}" class="action-btn" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('events.edit', $e) }}" class="action-btn" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="action-btn danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3 class="empty-title">Aucun événement planifié</h3>
                    <p class="empty-description">Il n'y a pas d'événement prévu pour cette période.</p>
                    <a href="{{ route('events.create') }}" class="empty-action">
                        <i class="bi bi-plus-circle me-2"></i>
                        Créer un événement
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const sections = document.querySelectorAll('[data-section]');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide sections
            sections.forEach(section => {
                if (filter === 'all') {
                    section.style.display = 'block';
                } else {
                    section.style.display = section.getAttribute('data-section') === filter ? 'block' : 'none';
                }
            });
        });
    });
});
</script>
@endsection


