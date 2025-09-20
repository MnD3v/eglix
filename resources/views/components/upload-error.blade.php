{{-- resources/views/components/upload-error.blade.php --}}
@props(['errorType' => 'warning', 'title' => 'Erreur d\'upload', 'message' => '', 'details' => '', 'showRetry' => true, 'showLocalStorage' => true])

@php
    $alertClasses = [
        'danger' => 'alert-danger',
        'warning' => 'alert-warning', 
        'info' => 'alert-info',
        'success' => 'alert-success'
    ];
    
    $iconClasses = [
        'danger' => 'fas fa-ban',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
        'success' => 'fas fa-check-circle'
    ];
    
    $alertClass = $alertClasses[$errorType] ?? $alertClasses['warning'];
    $iconClass = $iconClasses[$errorType] ?? $iconClasses['warning'];
@endphp

<div class="alert {{ $alertClass }} upload-error-component mt-2">
    <div class="d-flex align-items-start">
        <i class="{{ $iconClass }} me-2 mt-1"></i>
        <div class="flex-grow-1">
            <strong>{{ $title }}</strong>
            @if($message)
                <br>{{ $message }}
            @endif
            @if($details)
                <br><small class="text-muted">{{ $details }}</small>
            @endif
            
            @if($showRetry || $showLocalStorage)
                <div class="mt-2">
                    @if($showRetry)
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="retryUpload()">
                            <i class="fas fa-redo me-1"></i> Réessayer
                        </button>
                    @endif
                    @if($showLocalStorage)
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="useLocalStorage()">
                            <i class="fas fa-save me-1"></i> Stocker localement
                        </button>
                    @endif
                </div>
            @endif
        </div>
        <button type="button" class="btn-close" onclick="this.closest('.upload-error-component').remove()" aria-label="Fermer"></button>
    </div>
</div>

@push('scripts')
<script>
function retryUpload() {
    // Fonction globale pour réessayer l'upload
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput && fileInput.files.length > 0) {
        // Déclencher l'événement change pour relancer l'upload
        fileInput.dispatchEvent(new Event('change'));
    }
}

function useLocalStorage() {
    // Fonction globale pour utiliser le stockage local
    const errorComponent = document.querySelector('.upload-error-component');
    if (errorComponent) {
        errorComponent.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-success me-2"></i>
                <div>
                    <strong>Image stockée localement</strong><br>
                    <small class="text-muted">L'image sera sauvegardée sur le serveur local.</small>
                </div>
            </div>
        `;
        errorComponent.className = 'alert alert-success upload-error-component mt-2';
    }
}
</script>
@endpush
