@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Modifier membre</h1>
    <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data" class="card p-3">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input name="first_name" value="{{ old('first_name', $member->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Situation matrimoniale</label>
                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="single" @selected(old('marital_status', $member->marital_status)==='single')>Célibataire</option>
                    <option value="married" @selected(old('marital_status', $member->marital_status)==='married')>Marié(e)</option>
                    <option value="divorced" @selected(old('marital_status', $member->marital_status)==='divorced')>Divorcé(e)</option>
                    <option value="widowed" @selected(old('marital_status', $member->marital_status)==='widowed')>Veuf(ve)</option>
                </select>
                @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input name="last_name" value="{{ old('last_name', $member->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input name="email" type="email" value="{{ old('email', $member->email) }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input name="phone" value="{{ old('phone', $member->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Adresse</label>
                <input name="address" value="{{ old('address', $member->address) }}" class="form-control @error('address') is-invalid @enderror">
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
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
                <label class="form-label">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($member->birth_date)->format('Y-m-d')) }}" class="form-control @error('birth_date') is-invalid @enderror">
                @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Date de baptême</label>
                <input type="date" name="baptized_at" value="{{ old('baptized_at', optional($member->baptized_at)->format('Y-m-d')) }}" class="form-control @error('baptized_at') is-invalid @enderror">
                @error('baptized_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Pasteur ou responsable du baptême</label>
                <input name="baptism_responsible" value="{{ old('baptism_responsible', $member->baptism_responsible) }}" class="form-control @error('baptism_responsible') is-invalid @enderror" placeholder="Ex: Pasteur Jean Dupont">
                @error('baptism_responsible')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status', $member->status)==='active')>Actif</option>
                    <option value="inactive" @selected(old('status', $member->status)==='inactive')>Inactif</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Rejoint le</label>
                <input type="date" name="joined_at" value="{{ old('joined_at', optional($member->joined_at)->format('Y-m-d')) }}" class="form-control @error('joined_at') is-invalid @enderror">
                @error('joined_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $member->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Photo de profil</label>
                <div class="photo-upload-container">
                    <div class="photo-upload-area" id="photoUploadArea">
                        <div class="photo-upload-content">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <p class="mb-2">Glissez-déposez votre photo ici</p>
                            <p class="text-muted small">ou cliquez pour sélectionner</p>
                        </div>
                        <div class="photo-preview" id="photoPreview" style="display: none;">
                            <img id="previewImage" src="" alt="Aperçu" class="img-fluid rounded">
                            <div class="firebase-link mt-2" id="firebaseLink" style="display: none;">
                                <small class="text-muted">Lien Firebase :</small>
                                <div class="input-group input-group-sm">
                                    <input type="text" id="firebaseUrl" class="form-control form-control-sm" readonly>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyFirebaseLink()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removePhoto">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="d-none @error('profile_photo') is-invalid @enderror">
                    <div class="upload-progress" id="uploadProgress" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted">Upload en cours...</small>
                    </div>
                    @if($member->photo_url)
                        <div class="current-photo mt-2">
                            <p class="text-muted small mb-2">Photo actuelle :</p>
                            <img src="{{ $member->photo_url }}" alt="Photo actuelle" class="img-fluid rounded" style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px;">
                        </div>
                    @elseif($member->profile_photo)
                        <div class="current-photo mt-2">
                            <p class="text-muted small mb-2">Photo actuelle :</p>
                            <img src="{{ asset('storage/'.$member->profile_photo) }}" alt="Photo actuelle" class="img-fluid rounded" style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px;">
                        </div>
                    @endif
                    @error('profile_photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn">Enregistrer</button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.photo-upload-container {
    margin-bottom: 1rem;
}

.photo-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.photo-upload-area:hover {
    border-color: #ff2600;
    background-color: #fff5f5;
}

.photo-upload-area.dragover {
    border-color: #ff2600;
    background-color: #fff5f5;
    transform: scale(1.02);
}

.photo-upload-content {
    pointer-events: none;
}

.photo-preview {
    text-align: center;
}

.photo-preview img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.upload-progress {
    margin-top: 1rem;
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.progress-bar {
    background-color: #ff2600;
    transition: width 0.3s ease;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.current-photo {
    text-align: center;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.firebase-link {
    background-color: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.firebase-link .input-group {
    margin-top: 0.5rem;
}

.firebase-link input {
    font-size: 0.8rem;
    background-color: #fff;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('photoUploadArea');
    const fileInput = document.getElementById('profilePhotoInput');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const removePhotoBtn = document.getElementById('removePhoto');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress.querySelector('.progress-bar');

    // Click to select file
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Drag and drop events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                handleFile(file);
            } else {
                showError('Veuillez sélectionner un fichier image valide.');
            }
        }
    });

    // Remove photo
    removePhotoBtn.addEventListener('click', function() {
        fileInput.value = '';
        photoPreview.style.display = 'none';
        uploadArea.querySelector('.photo-upload-content').style.display = 'block';
        uploadProgress.style.display = 'none';
        document.getElementById('firebaseLink').style.display = 'none';
    });

    function handleFile(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showError('Veuillez sélectionner un fichier image valide.');
            return;
        }

        // Validate file size (4MB max)
        if (file.size > 4 * 1024 * 1024) {
            showError('Le fichier est trop volumineux. Taille maximale : 4MB.');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            photoPreview.style.display = 'block';
            uploadArea.querySelector('.photo-upload-content').style.display = 'none';
            
            // Simulate upload progress
            simulateUploadProgress();
        };
        reader.readAsDataURL(file);
    }

    function simulateUploadProgress() {
        uploadProgress.style.display = 'block';
        let progress = 0;
        
        const interval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress > 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(function() {
                    uploadProgress.style.display = 'none';
                    // Simuler un échec d'upload pour le moment
                    showUploadError('L\'upload vers Firebase a échoué. L\'image sera stockée localement.');
                }, 500);
            }
            progressBar.style.width = progress + '%';
        }, 200);
    }

    function showFirebaseLink(firebaseUrl) {
        if (firebaseUrl) {
            document.getElementById('firebaseUrl').value = firebaseUrl;
            document.getElementById('firebaseLink').style.display = 'block';
        }
    }

    function showUploadError(message) {
        // Afficher un message d'erreur au lieu d'un lien fictif
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-warning mt-2';
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        `;
        
        // Insérer le message d'erreur après l'aperçu
        const photoPreview = document.getElementById('photoPreview');
        photoPreview.appendChild(errorDiv);
        
        // Supprimer le message après 5 secondes
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    function copyFirebaseLink() {
        const firebaseUrlInput = document.getElementById('firebaseUrl');
        firebaseUrlInput.select();
        firebaseUrlInput.setSelectionRange(0, 99999); // Pour mobile
        
        try {
            document.execCommand('copy');
            // Afficher un message de confirmation
            const button = event.target.closest('button');
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check text-success"></i>';
            setTimeout(() => {
                button.innerHTML = originalIcon;
            }, 2000);
        } catch (err) {
            console.error('Erreur lors de la copie:', err);
        }
    }

    function showError(message) {
        // Remove existing error messages
        const existingError = uploadArea.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        uploadArea.appendChild(errorDiv);

        // Remove error after 5 seconds
        setTimeout(function() {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endpush
