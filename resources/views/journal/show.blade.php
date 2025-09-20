@extends('layouts.app')

@section('content')
<style>
/* Design moderne pour la page de détail du journal */
.journal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px;
    padding: 2rem;
    margin: 0 0 2rem 0;
    position: relative;
    overflow: hidden;
}

.journal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.journal-header-content {
    position: relative;
    z-index: 2;
}

.journal-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.journal-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.journal-date {
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.journal-category {
    background: rgba(255,255,255,0.15);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 400;
    backdrop-filter: blur(10px);
}

.journal-actions {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    display: flex;
    gap: 0.75rem;
}

.journal-action-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.journal-action-btn:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.journal-content {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.journal-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #374151;
    white-space: pre-wrap;
}

.journal-description::first-letter {
    font-size: 3rem;
    font-weight: 700;
    float: left;
    line-height: 1;
    margin: 0.1rem 0.5rem 0 0;
    color: #667eea;
}

/* Galerie d'images moderne */
.image-gallery {
    position: relative;
}

.gallery-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.gallery-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.gallery-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 500;
    font-size: 0.9rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #e5e7eb;
}

.gallery-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.gallery-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-icon {
    color: white;
    font-size: 2.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

.gallery-info {
    padding: 1rem;
    background: #f8f9fa;
    border-top: 1px solid #e5e7eb;
}

.gallery-title-item {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Modal pour l'affichage en grand */
.image-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.95);
    animation: fadeIn 0.3s ease;
}

.image-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    max-width: 90%;
    max-height: 90%;
    position: relative;
    animation: zoomIn 0.3s ease;
}

.modal-image {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
}

.modal-close {
    position: absolute;
    top: -50px;
    right: 0;
    color: white;
    font-size: 2.5rem;
    cursor: pointer;
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
    backdrop-filter: blur(10px);
}

.modal-close:hover {
    background: rgba(0,0,0,0.8);
}

.modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    font-size: 2rem;
    cursor: pointer;
    background: rgba(0,0,0,0.6);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
    backdrop-filter: blur(10px);
}

.modal-nav:hover {
    background: rgba(0,0,0,0.8);
}

.modal-prev {
    left: -70px;
}

.modal-next {
    right: -70px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes zoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Responsive design */
@media (max-width: 768px) {
    .journal-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .journal-title {
        font-size: 2rem;
    }
    
    .journal-actions {
        position: static;
        margin-top: 1rem;
        justify-content: center;
    }
    
    .journal-meta {
        justify-content: center;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .gallery-image {
        height: 150px;
    }
    
    .modal-nav {
        display: none;
    }
    
    .modal-close {
        top: -40px;
        right: -10px;
        width: 40px;
        height: 40px;
        font-size: 2rem;
    }
    
    .journal-content {
        padding: 1.5rem;
    }
}
</style>

<div class="container py-4">
    @include('partials.back-button')
    
    <!-- En-tête moderne du journal -->
    <div class="journal-header">
        <div class="journal-header-content">
            <h1 class="journal-title">{{ $entry->title }}</h1>
            <div class="journal-meta">
                <div class="journal-date">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ optional($entry->occurred_at)->format('d F Y') }}
                </div>
                @if($entry->category)
                <div class="journal-category">
                    <i class="fas fa-tag me-2"></i>
                    {{ $entry->category }}
                </div>
                @endif
            </div>
        </div>
        <div class="journal-actions">
            <a href="{{ route('journal.edit', $entry) }}" class="journal-action-btn">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('journal.index') }}" class="journal-action-btn">
                <i class="fas fa-list me-2"></i>Journal
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="journal-content">
        <div class="journal-description">{{ $entry->description }}</div>
    </div>

    <!-- Galerie d'images -->
    @if($entry->images->count())
    <div class="card card-soft p-3">
        <div class="gallery-header">
            <h2 class="gallery-title">
                <i class="fas fa-images me-2"></i>Galerie d'images
            </h2>
            <span class="gallery-count">{{ $entry->images->count() }} image{{ $entry->images->count() > 1 ? 's' : '' }}</span>
        </div>
        
        <div class="image-gallery">
            <div class="gallery-grid">
                @foreach($entry->images as $index => $img)
                <div class="gallery-item" onclick="openModal({{ $index }})">
                    <img src="{{ str_starts_with($img->path, 'http') ? $img->path : asset('storage/'.$img->path) }}" 
                         alt="Image {{ $index + 1 }}" 
                         class="gallery-image"
                         loading="lazy">
                    <div class="gallery-overlay">
                        <i class="fas fa-search-plus gallery-icon"></i>
                    </div>
                    <div class="gallery-info">
                        <p class="gallery-title-item">Image {{ $index + 1 }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal pour l'affichage en grand -->
<div id="imageModal" class="image-modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-nav modal-prev" onclick="previousImage()">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="modal-nav modal-next" onclick="nextImage()">
            <i class="fas fa-chevron-right"></i>
        </div>
        <img id="modalImage" class="modal-image" src="" alt="">
    </div>
</div>

<script>
// Données des images pour le modal
const images = [
    @foreach($entry->images as $img)
    '{{ str_starts_with($img->path, 'http') ? $img->path : asset('storage/'.$img->path) }}',
    @endforeach
];

let currentImageIndex = 0;

function openModal(index) {
    currentImageIndex = index;
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = images[currentImageIndex];
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    document.getElementById('modalImage').src = images[currentImageIndex];
}

function previousImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    document.getElementById('modalImage').src = images[currentImageIndex];
}

// Navigation au clavier
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageModal');
    if (modal.classList.contains('show')) {
        switch(e.key) {
            case 'Escape':
                closeModal();
                break;
            case 'ArrowLeft':
                previousImage();
                break;
            case 'ArrowRight':
                nextImage();
                break;
        }
    }
});

// Fermer le modal en cliquant à l'extérieur
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection