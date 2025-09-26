@extends('layouts.app')

@section('content')
<style>
/* Design cohérent pour la page de détail du journal */
.journal-detail-header {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}

.journal-detail-info {
    flex: 1;
    min-width: 0;
}

.journal-detail-title {
    font-size: 24px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.journal-detail-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.journal-detail-date {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #000000;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
}

.journal-detail-category {
    background: #f1f5f9;
    color: #64748b;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
}

.journal-detail-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.journal-detail-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.journal-detail-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #475569;
    text-decoration: none;
}

.journal-detail-btn.btn-primary {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff;
}

.journal-detail-btn.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}

.journal-content {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.journal-description {
    font-size: 16px;
    line-height: 1.6;
    color: #374151;
    white-space: pre-wrap;
    margin: 0;
}

/* Galerie d'images cohérente */
.image-gallery {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.gallery-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.gallery-title {
    font-size: 18px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.gallery-count {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #000000;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    cursor: pointer;
}

.gallery-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
    background: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-icon {
    color: white;
    font-size: 2rem;
}

.gallery-info {
    padding: 12px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.gallery-title-item {
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
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
    .journal-detail-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .journal-detail-title {
        font-size: 20px;
    }
    
    .journal-detail-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .journal-detail-meta {
        justify-content: flex-start;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
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
        padding: 1rem;
    }
    
    .image-gallery {
        padding: 1rem;
    }
}
</style>

<div class="container py-4">
    @include('partials.back-button')
    
    <!-- En-tête cohérent du journal -->
    <div class="journal-detail-header">
        <div class="journal-detail-info">
            <h1 class="journal-detail-title">{{ $entry->title }}</h1>
            <div class="journal-detail-meta">
                <div class="journal-detail-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ optional($entry->occurred_at)->format('d/m/Y') }}
                </div>
                @if($entry->category)
                <div class="journal-detail-category">
                    <i class="bi bi-tag me-1"></i>
                    {{ $entry->category }}
                </div>
                @endif
            </div>
        </div>
        <div class="journal-detail-actions">
            <a href="{{ route('journal.edit', $entry) }}" class="journal-detail-btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Modifier
            </a>
            <a href="{{ route('journal.index') }}" class="journal-detail-btn">
                <i class="bi bi-list me-1"></i>Journal
            </a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="journal-content">
        <div class="journal-description">{{ $entry->description }}</div>
    </div>

    <!-- Galerie d'images -->
    @if($entry->images->count())
    <div class="image-gallery">
        <div class="gallery-header">
            <h2 class="gallery-title">
                <i class="bi bi-images"></i>Galerie d'images
            </h2>
            <span class="gallery-count">{{ $entry->images->count() }} image{{ $entry->images->count() > 1 ? 's' : '' }}</span>
        </div>
        
        <div class="gallery-grid">
            @foreach($entry->images as $index => $img)
            <div class="gallery-item" onclick="openModal({{ $index }})">
                <img src="{{ str_starts_with($img->path, 'http') ? $img->path : asset('storage/'.$img->path) }}" 
                     alt="Image {{ $index + 1 }}" 
                     class="gallery-image"
                     loading="lazy">
                <div class="gallery-overlay">
                    <i class="bi bi-zoom-in gallery-icon"></i>
                </div>
                <div class="gallery-info">
                    <p class="gallery-title-item">Image {{ $index + 1 }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Modal pour l'affichage en grand -->
<div id="imageModal" class="image-modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-nav modal-prev" onclick="previousImage()">
            <i class="bi bi-chevron-left"></i>
        </div>
        <div class="modal-nav modal-next" onclick="nextImage()">
            <i class="bi bi-chevron-right"></i>
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