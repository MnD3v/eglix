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

/* Event Detail Card */
.event-detail-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.2s ease;
}

.event-detail-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.event-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 2rem;
}

.event-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
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
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.event-date {
    background: #8B5CF6;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.event-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.event-detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.event-detail-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.event-detail-value {
    color: #1F2937;
    font-size: 1rem;
    line-height: 1.5;
}

.event-description {
    background: #F9FAFB;
    border-radius: 6px;
    padding: 1.5rem;
    color: #374151;
    line-height: 1.6;
    font-size: 1rem;
}

/* Gallery Section */
.gallery-section {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.gallery-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.gallery-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.gallery-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin: 0;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #E5E7EB;
    transition: all 0.2s ease;
}

.gallery-item:hover {
    border-color: #D1D5DB;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay i {
    color: white;
    font-size: 1.5rem;
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
</style>

<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ $event->title }}</h1>
        <p class="page-subtitle">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</p>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ url()->previous() }}" class="action-btn">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
        <a href="{{ route('events.edit', $event) }}" class="action-btn primary">
            <i class="bi bi-pencil"></i>
            Modifier
        </a>
    </div>

    <!-- Event Details -->
    <div class="event-detail-card">
        <div class="event-header">
            <div class="event-icon {{ strtolower($event->type ?? 'other') }}">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="event-info">
                <h2 class="event-title">{{ $event->title }}</h2>
                <div class="event-date">
                    <i class="bi bi-calendar3"></i>
                    {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}
                </div>
            </div>
        </div>

        <div class="event-details">
            @if($event->type)
            <div class="event-detail-item">
                <span class="event-detail-label">Type</span>
                <span class="event-detail-value">{{ $event->type }}</span>
            </div>
            @endif

            @if($event->start_time || $event->end_time)
            <div class="event-detail-item">
                <span class="event-detail-label">Horaires</span>
                <span class="event-detail-value">
                    @if($event->start_time && $event->end_time)
                        {{ $event->start_time }} - {{ $event->end_time }}
                    @elseif($event->start_time)
                        À partir de {{ $event->start_time }}
                    @elseif($event->end_time)
                        Jusqu'à {{ $event->end_time }}
                    @endif
                </span>
            </div>
            @endif

            @if($event->location)
            <div class="event-detail-item">
                <span class="event-detail-label">Lieu</span>
                <span class="event-detail-value">{{ $event->location }}</span>
            </div>
            @endif
        </div>

        @if($event->description)
        <div class="event-description">
            {{ $event->description }}
        </div>
        @endif
    </div>

    <!-- Gallery -->
    @if(!empty($event->images) && count(array_filter($event->images)) > 0)
    <div class="gallery-section">
        <div class="gallery-header">
            <div class="gallery-icon">
                <i class="bi bi-images"></i>
            </div>
            <h3 class="gallery-title">Galerie d'images</h3>
        </div>
        <div class="gallery-grid">
            @foreach(array_filter($event->images) as $img)
            <div class="gallery-item">
                <img src="{{ $img }}" alt="Image de l'événement">
                <a href="{{ $img }}" target="_blank" rel="noopener" class="gallery-overlay">
                    <i class="bi bi-zoom-in"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection