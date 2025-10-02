@extends('layouts.app')

@section('content')
<style>
/* Styles pour les champs de formulaire arrondis */
.form-control, .form-select, .form-label {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les sections du formulaire */
.form-section {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* Styles pour les boutons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary i {
    color: #000000 !important;
}

.btn-primary:hover i {
    color: #000000 !important;
}

.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-outline-secondary i {
    color: #000000 !important;
}

.btn-outline-secondary:hover i {
    color: #000000 !important;
}

.btn-primary,
.btn-outline-secondary,
.upload-button {
    font-weight: 700 !important;
}

/* Styles pour les select dropdowns */
.form-select:focus {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

.form-select option:checked,
.form-select option:hover {
    background-color: #FFCC00 !important;
    color: #000000 !important;
}

/* Override Bootstrap select color */
select.form-select:focus,
select.form-select:active {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

/* Styles pour les options des dropdowns */
.form-select option:hover,
.form-select option:focus,
.form-select option:checked {
    background-color: #FFCC00 !important;
    color: #000000 !important;
}

/* Style global pour tous les select */
select {
    border-color: #e2e8f0 !important;
}

select:focus {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Membre -->
    <div class="appbar members-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('members.show', $member) }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier {{ $member->last_name }} {{ $member->first_name }}</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Section Informations Personnelles -->
        <div class="form-section">
            <h2 class="section-title">Informations Personnelles</h2>
            <p class="section-subtitle">Les informations de base du membre</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input name="first_name" value="{{ old('first_name', $member->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required placeholder="Ex: Jean">
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input name="last_name" value="{{ old('last_name', $member->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required placeholder="Ex: Dupont">
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" value="{{ old('email', $member->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Ex: jean.dupont@email.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input name="phone" value="{{ old('phone', $member->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: +237 6XX XXX XXX">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Adresse</label>
                    <input name="address" value="{{ old('address', $member->address) }}" class="form-control @error('address') is-invalid @enderror" placeholder="Ex: Quartier Bastos, Yaoundé">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Détails Personnels -->
        <div class="form-section">
            <h2 class="section-title">Détails Personnels</h2>
            <p class="section-subtitle">Informations complémentaires sur le membre</p>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Sexe</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="male" @selected(old('gender', $member->gender)==='male')>Homme</option>
                        <option value="female" @selected(old('gender', $member->gender)==='female')>Femme</option>
                        <option value="other" @selected(old('gender', $member->gender)==='other')>Autre</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Situation Matrimoniale</label>
                    <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="single" @selected(old('marital_status', $member->marital_status)==='single')>Célibataire</option>
                        <option value="married" @selected(old('marital_status', $member->marital_status)==='married')>Marié(e)</option>
                        <option value="divorced" @selected(old('marital_status', $member->marital_status)==='divorced')>Divorcé(e)</option>
                        <option value="widowed" @selected(old('marital_status', $member->marital_status)==='widowed')>Veuf(ve)</option>
                    </select>
                    @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date de Naissance</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}" class="form-control @error('birth_date') is-invalid @enderror">
                    @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label class="form-label">Domaine d'Activité</label>
                    <input name="activity_domain" value="{{ old('activity_domain', $member->activity_domain) }}" class="form-control @error('activity_domain') is-invalid @enderror" placeholder="Ex: Informatique, Médecine, Enseignement, Commerce...">
                    @error('activity_domain')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Informations Spirituelles -->
        <div class="form-section">
            <h2 class="section-title">Informations Spirituelles</h2>
            <p class="section-subtitle">Détails sur le parcours spirituel du membre</p>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Date de Baptême</label>
                    <input type="date" name="baptized_at" value="{{ old('baptized_at', optional($member->baptized_at)->format('Y-m-d')) }}" class="form-control @error('baptized_at') is-invalid @enderror">
                    @error('baptized_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pasteur ou Responsable du Baptême</label>
                    <input name="baptism_responsible" value="{{ old('baptism_responsible', $member->baptism_responsible) }}" class="form-control @error('baptism_responsible') is-invalid @enderror" placeholder="Ex: Pasteur Jean Dupont">
                    @error('baptism_responsible')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date d'Adhésion</label>
                    <input type="date" name="joined_at" value="{{ old('joined_at', optional($member->joined_at)->format('Y-m-d')) }}" class="form-control @error('joined_at') is-invalid @enderror">
                    @error('joined_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Statut et Notes -->
        <div class="form-section">
            <h2 class="section-title">Statut et Notes</h2>
            <p class="section-subtitle">Gestion du statut et informations complémentaires</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" @selected(old('status', $member->status)==='active')>Actif</option>
                        <option value="inactive" @selected(old('status', $member->status)==='inactive')>Inactif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo de Profil</label>
                    <div class="photo-upload-container">
                        <div class="photo-upload-area" id="photoUploadArea">
                            <div class="photo-upload-content">
                                <button type="button" class="upload-button" id="uploadButton">
                                    <i class="bi bi-camera me-2"></i>Modifier la Photo
                                </button>
                                <div class="upload-text">
                                    <p class="upload-title">Ou déposez une nouvelle image ici</p>
                                </div>
                                <div class="upload-formats">
                                    <small class="text-muted">JPG, PNG, WEBP • Max 4MB</small>
                                </div>
                            </div>
                            <div class="photo-preview" id="photoPreview" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="previewImage" src="" alt="Aperçu" class="img-fluid rounded shadow-sm" style="width: 200px; height: 200px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" id="removePhoto" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Supprimer l'image">
                                        <i class="bi bi-x" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="d-none @error('profile_photo') is-invalid @enderror">
                        <div class="upload-progress" id="uploadProgress" style="display: none;">
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" id="progressBar"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-cloud-upload me-1"></i>
                                    <span id="progressText">Upload en cours...</span>
                                </small>
                                <small class="text-muted" id="progressPercent">0%</small>
                            </div>
                        </div>
                        @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes complémentaires sur le membre...">{{ old('notes', $member->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* Styles pour l'upload de photo */
.photo-upload-container {
    margin-top: 1rem;
}

.photo-upload-area {
    border: 2px dashed #e2e8f0;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    background: #f8fafc;
    transition: all 0.3s ease;
    cursor: pointer;
}

.photo-upload-area:hover {
    border-color: #FFCC00;
    background: #fffef7;
}

.photo-upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.upload-button {
    background: #FFCC00;
    color: #000000;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all 0.3s ease;
}

.upload-button:hover {
    background: #e6b800;
    transform: translateY(-1px);
}

.upload-button i {
    color: #000000 !important;
}

.upload-text {
    text-align: center;
}

.upload-title {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
    font-weight: 500;
}

.upload-formats {
    margin-top: 0.5rem;
}

.photo-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.upload-progress {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background: #e2e8f0;
}

.progress-bar {
    background: #FFCC00;
    border-radius: 4px;
    transition: width 0.3s ease;
}

/* Affichage de la photo actuelle */
.current-photo {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    text-align: center;
}

.current-photo img {
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.current-photo img:hover {
    border-color: #FFCC00;
    transform: scale(1.02);
}

/* Forcer toutes les icônes à être noires */
.btn-danger i {
    color: #ffffff !important;
}

.upload-progress i {
    color: #64748b !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('photoUploadArea');
    const fileInput = document.getElementById('profilePhotoInput');
    const preview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const removePhoto = document.getElementById('removePhoto');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');

    // Gestion du clic sur la zone d'upload
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // Gestion du drag & drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#FFCC00';
        uploadArea.style.background = '#fffef7';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });

    // Gestion de la sélection de fichier
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });

    // Gestion de la suppression de photo
    removePhoto.addEventListener('click', function() {
        preview.style.display = 'none';
        fileInput.value = '';
        uploadArea.querySelector('.photo-upload-content').style.display = 'flex';
    });

    function handleFile(file) {
        // Vérification de la taille
        if (file.size > 4 * 1024 * 1024) {
            alert('Le fichier est trop volumineux. Taille maximale : 4MB');
            return;
        }

        // Vérification du type
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image');
            return;
        }

        // Affichage de l'aperçu
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.style.display = 'flex';
            uploadArea.querySelector('.photo-upload-content').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection

