@extends('layouts.app')

@section('title', 'Modifier le Dossier')

@section('content')
<style>
/* Styles modernes pour l'édition de dossier */
.form-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.form-section:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control, .form-select {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #ffffff;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.1);
    background-color: #ffffff;
}

.form-control-color {
    width: 60px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
}

.form-control-color:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.1);
}

.preview-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.preview-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.preview-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-right: 1rem;
}

.preview-content h5 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.preview-content p {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
}

.status-inactive {
    background-color: #fef2f2;
    color: #dc2626;
}

.info-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.alert-modern {
    border: none;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.alert-info-modern {
    background: #eff6ff;
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.alert-warning-modern {
    background: #fffbeb;
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

.btn-modern {
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #FFCC00, #e02200);
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 204, 0, 0.3);
    color: white;
}

.btn-secondary-modern {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-secondary-modern:hover {
    background: #e2e8f0;
    color: #374151;
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-modern {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Dossier -->
    <div class="appbar documents-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('document-folders.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier le Dossier</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('document-folders.update', $documentFolder) }}" method="POST" id="folderForm">
                @csrf
                @method('PUT')
                
                <!-- Section Informations du Dossier -->
                <div class="form-section">
                    <h2 class="section-title">Informations du Dossier</h2>
                    <p class="section-subtitle">Modifiez les détails de votre dossier de documents</p>
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nom du dossier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $documentFolder->name) }}" 
                                   required placeholder="Ex: Rapports annuels">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="color" class="form-label">Couleur</label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" name="color" value="{{ old('color', $documentFolder->color) }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="form-section">
                    <h2 class="section-title">Description</h2>
                    <p class="section-subtitle">Ajoutez une description pour ce dossier</p>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description du dossier</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Décrivez le contenu et l'objectif de ce dossier...">{{ old('description', $documentFolder->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Section Paramètres -->
                <div class="form-section">
                    <h2 class="section-title">Paramètres</h2>
                    <p class="section-subtitle">Configurez les options d'affichage et de statut</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" name="sort_order" value="{{ old('sort_order', $documentFolder->sort_order) }}" 
                                   min="0" placeholder="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $documentFolder->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Dossier actif</strong>
                                    <small class="text-muted d-block">Le dossier sera visible dans la liste</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Aperçu -->
                <div class="form-section">
                    <h2 class="section-title">Aperçu</h2>
                    <p class="section-subtitle">Visualisez l'apparence de votre dossier</p>
                    
                    <div class="preview-card">
                        <div class="d-flex align-items-start">
                            <div class="preview-icon" id="previewIcon">
                                <i class="bi bi-folder-fill"></i>
                            </div>
                            <div class="preview-content flex-grow-1">
                                <h5 id="previewName">{{ $documentFolder->name }}</h5>
                                <p id="previewDescription">{{ $documentFolder->description ?: 'Description du dossier' }}</p>
                                <span class="status-badge" id="previewStatus">
                                    {{ $documentFolder->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('document-folders.index') }}" class="btn btn-secondary-modern btn-modern">
                        <i class="bi bi-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary-modern btn-modern">
                        <i class="bi bi-check-lg me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <!-- Informations du dossier -->
            <div class="info-card">
                <h3 class="info-title">
                    <i class="bi bi-info-circle"></i>
                    Informations
                </h3>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">{{ $documentFolder->documents_count }}</div>
                        <div class="stat-label">Documents</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $documentFolder->formatted_size }}</div>
                        <div class="stat-label">Taille totale</div>
                    </div>
                </div>
                
                <div class="alert-modern alert-info-modern">
                    <h6 class="fw-bold mb-2">Détails du dossier :</h6>
                    <ul class="mb-0 small">
                        <li><strong>Créé le :</strong> {{ $documentFolder->created_at->format('d/m/Y à H:i') }}</li>
                        <li><strong>Modifié le :</strong> {{ $documentFolder->updated_at->format('d/m/Y à H:i') }}</li>
                        <li><strong>Créé par :</strong> {{ $documentFolder->creator->name ?? 'Utilisateur' }}</li>
                    </ul>
                </div>

                @if($documentFolder->documents_count > 0)
                    <div class="alert-modern alert-warning-modern">
                        <h6 class="fw-bold mb-2">⚠️ Attention :</h6>
                        <p class="mb-0 small">Ce dossier contient <strong>{{ $documentFolder->documents_count }} document(s)</strong>. 
                        La modification du nom ou de la couleur n'affectera pas les documents existants.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const isActiveInput = document.getElementById('is_active');
    
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const previewIcon = document.getElementById('previewIcon');
    const previewStatus = document.getElementById('previewStatus');

    function updatePreview() {
        const name = nameInput.value || 'Nom du dossier';
        const description = descriptionInput.value || 'Description du dossier';
        const color = colorInput.value;
        const isActive = isActiveInput.checked;

        previewName.textContent = name;
        previewDescription.textContent = description;
        previewIcon.style.backgroundColor = color;
        
        if (isActive) {
            previewStatus.textContent = 'Actif';
            previewStatus.className = 'status-badge status-active';
        } else {
            previewStatus.textContent = 'Inactif';
            previewStatus.className = 'status-badge status-inactive';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updatePreview);
    isActiveInput.addEventListener('change', updatePreview);

    // Initialiser l'aperçu
    updatePreview();
});
</script>
@endsection