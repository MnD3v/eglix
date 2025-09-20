@extends('layouts.app')

@section('content')
<style>
/* Design simple et épuré pour la sélection d'image */
.photo-upload-container {
    margin-bottom: 1rem;
}

.photo-upload-area {
    border: none;
    border-radius: 0;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    position: relative;
    overflow: hidden;
}

.photo-upload-area:hover {
    background: #ffffff;
}

.photo-upload-area.dragover {
    background: #ffffff;
}

.upload-button {
    background: white !important;
    border: 1px solid #333 !important;
    border-radius: 4px !important;
    padding: 12px 24px !important;
    color: #333 !important;
    font-weight: 400 !important;
    font-size: 1rem !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    margin-bottom: 1rem !important;
    display: inline-block !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    box-shadow: none !important;
}

.upload-button:hover {
    background: #ffffff !important;
    border-color: #000 !important;
}

.upload-button:active {
    background: #e9e9e9 !important;
}

.upload-text {
    margin-bottom: 0.5rem;
}

.upload-title {
    font-size: 1rem;
    font-weight: 400;
    color: #333;
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.upload-subtitle {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

.upload-click {
    color: #333;
    font-weight: 400;
    text-decoration: underline;
    cursor: pointer;
}

.upload-formats {
    margin-top: 1rem;
}

.photo-upload-content {
    pointer-events: none;
    position: relative;
    z-index: 2;
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

/* Responsive design */
@media (max-width: 768px) {
    .photo-upload-area {
        padding: 1.5rem 1rem;
    }
    
    .upload-button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}
</style>
<div class="container py-4">
    <h1 class="h3 mb-3">Nouveau membre</h1>
    <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" class="card p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Adresse</label>
                <input name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Sexe</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="male" @selected(old('gender')==='male')>Homme</option>
                    <option value="female" @selected(old('gender')==='female')>Femme</option>
                    <option value="other" @selected(old('gender')==='other')>Autre</option>
                </select>
                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Situation matrimoniale</label>
                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="single" @selected(old('marital_status')==='single')>Célibataire</option>
                    <option value="married" @selected(old('marital_status')==='married')>Marié(e)</option>
                    <option value="divorced" @selected(old('marital_status')==='divorced')>Divorcé(e)</option>
                    <option value="widowed" @selected(old('marital_status')==='widowed')>Veuf(ve)</option>
                </select>
                @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control @error('birth_date') is-invalid @enderror">
                @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Date de baptême</label>
                <input type="date" name="baptized_at" value="{{ old('baptized_at') }}" class="form-control @error('baptized_at') is-invalid @enderror">
                @error('baptized_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" @selected(old('status','active')==='active')>Actif</option>
                    <option value="inactive" @selected(old('status')==='inactive')>Inactif</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Rejoint le</label>
                <input type="date" name="joined_at" value="{{ old('joined_at') }}" class="form-control @error('joined_at') is-invalid @enderror">
                @error('joined_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Photo de profil</label>
                <div class="photo-upload-container">
                    <div class="photo-upload-area" id="photoUploadArea">
                        <div class="photo-upload-content">
                            <button type="button" class="upload-button" id="uploadButton">
                                Commencer avec une photo
                            </button>
                            <div class="upload-text">
                                <p class="upload-title">Ou déposez une image ici</p>
                            </div>
                            <div class="upload-formats">
                                <small class="text-muted">JPG, PNG, WEBP • Max 4MB</small>
                            </div>
                        </div>
                        <div class="photo-preview" id="photoPreview" style="display: none;">
                            <div class="position-relative d-inline-block">
                                <img id="previewImage" src="" alt="Aperçu" class="img-fluid rounded shadow-sm" style="width: 200px; height: 200px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" id="removePhoto" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Supprimer l'image">
                                    <i class="fas fa-times" style="font-size: 12px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="d-none @error('profile_photo') is-invalid @enderror">
                    <div class="upload-progress" id="uploadProgress" style="display: none;">
                        <div class="progress mb-2" style="height: 12px; border-radius: 15px; background-color: #ffffff; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="progress-bar" role="progressbar" style="width: 0%; border-radius: 15px; background: linear-gradient(90deg, #007bff, #0056b3); transition: width 0.2s ease; box-shadow: 0 2px 4px rgba(0,123,255,0.3);" id="progressBar"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-cloud-upload me-1"></i>
                                <span id="progressText">Upload en cours...</span>
                            </small>
                            <small class="text-primary fw-bold" id="progressPercent">0%</small>
                        </div>
                    </div>
                    @error('profile_photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn">
                <span class="btn-text">Enregistrer</span>
            </button>
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
    background-color: #ffffff;
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
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const progressPercent = document.getElementById('progressPercent');

    // Gestion du bouton et de la zone de clic
    const uploadButton = document.getElementById('uploadButton');
    
    uploadButton.addEventListener('click', function(e) {
        e.stopPropagation();
        fileInput.click();
    });
    
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
        // Masquer le lien Firebase
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
            
            // Start real upload progress
            startRealUploadProgress();
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
                    // Simuler l'affichage du lien Firebase après l'upload
                    showFirebaseLink();
                }, 500);
            }
            progressBar.style.width = progress + '%';
        }, 200);
    }

    function showFirebaseLink() {
        // Simuler un lien Firebase (en production, ceci viendrait du serveur)
        const firebaseUrl = 'https://firebasestorage.googleapis.com/v0/b/xboite-d7c80.firebasestorage.app/o/member_photos/' + 
                           Math.random().toString(36).substring(7) + '.jpg?alt=media';
        
        // Afficher le lien Firebase
    }

    function startRealUploadProgress() {
        uploadProgress.style.display = 'block';
        progressBar.style.width = '0%';
        progressPercent.textContent = '0%';
        progressText.textContent = 'Préparation...';
        
        // Procéder directement à l'upload Firebase réel
        proceedWithRealFirebaseUpload();
    }
    
    async function proceedWithRealFirebaseUpload() {
        try {
            // Importer Firebase dynamiquement
            const { initializeApp } = await import("https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js");
            const { getStorage, ref, uploadBytesResumable, getDownloadURL } = await import("https://www.gstatic.com/firebasejs/9.23.0/firebase-storage.js");
            
            // Configuration Firebase exacte depuis google-services.json
            const firebaseConfig = {
                apiKey: "AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk",
                authDomain: "xboite-d7c80.firebaseapp.com",
                projectId: "xboite-d7c80",
                storageBucket: "xboite-d7c80.firebasestorage.app",
                messagingSenderId: "457797490593",
                appId: "1:457797490593:web:eglix-web"
            };
            
            console.log('Initialisation Firebase avec configuration réelle:', firebaseConfig);
            
            // Initialiser Firebase
            const app = initializeApp(firebaseConfig);
            const storage = getStorage(app);
            
            // Obtenir le fichier sélectionné
            const fileInput = document.getElementById('profilePhotoInput');
            const file = fileInput.files[0];
            
            if (!file) {
                showUploadError('Aucun fichier sélectionné', 'Veuillez sélectionner une image.', 'file_missing');
                return;
            }
            
            // Créer une référence Firebase Storage
            const fileName = `member_photos/${Date.now()}_${file.name}`;
            const storageRef = ref(storage, fileName);
            
            console.log('Upload vers Firebase Storage:', fileName);
            
            // Upload du fichier avec suivi de progression réel
            const uploadTask = uploadBytesResumable(storageRef, file);
            
            // Suivre la progression de l'upload
            uploadTask.on('state_changed', 
                (snapshot) => {
                    // Calculer le pourcentage de progression
                    const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                    const progressPercent = Math.round(progress);
                    
                    // Mettre à jour la barre de progression
                    progressBar.style.width = progressPercent + '%';
                    progressPercent.textContent = progressPercent + '%';
                    
                    // Mettre à jour le texte selon le pourcentage
                    if (progressPercent < 30) {
                        progressText.textContent = 'Préparation...';
                    } else if (progressPercent < 70) {
                        progressText.textContent = 'Upload vers Firebase...';
                    } else if (progressPercent < 100) {
                        progressText.textContent = 'Finalisation...';
                    } else {
                        progressText.textContent = 'Terminé!';
                    }
                    
                    console.log('Progression réelle:', progressPercent + '%');
                },
                (error) => {
                    console.error('Erreur pendant l\'upload:', error);
                    throw error;
                },
                async () => {
                    // Upload terminé avec succès
                    console.log('Upload réussi!');
                    
                    // Obtenir l'URL de téléchargement
                    const downloadURL = await getDownloadURL(uploadTask.snapshot.ref);
                    console.log('URL Firebase obtenue:', downloadURL);
                    
                    // Masquer la barre de progression après un délai
                    setTimeout(() => {
                    uploadProgress.style.display = 'none';
                    }, 1000);
                    
                    // Afficher le succès avec l'URL Firebase
                    showFirebaseSuccess(downloadURL);
                }
            );
            
        } catch (error) {
            console.error('Erreur Firebase réelle:', error);
            
            // Gestion des vraies erreurs Firebase
            let errorMessage = 'Erreur Firebase';
            let errorDetails = '';
            let errorType = 'firebase_error';
            
            if (error.code) {
                switch (error.code) {
                    case 'storage/unauthorized':
                        errorMessage = 'Accès non autorisé à Firebase Storage';
                        errorDetails = 'Vérifiez les règles de sécurité Firebase Storage dans la console Firebase.';
                        errorType = 'permission';
                        break;
                    case 'storage/quota-exceeded':
                        errorMessage = 'Quota de stockage Firebase dépassé';
                        errorDetails = 'L\'espace de stockage Firebase est plein. Contactez l\'administrateur Firebase.';
                        errorType = 'quota';
                        break;
                    case 'storage/network-request-failed':
                        errorMessage = 'Erreur de connexion réseau';
                        errorDetails = 'Impossible de se connecter à Firebase. Vérifiez votre connexion internet.';
                        errorType = 'network';
                        break;
                    case 'storage/invalid-format':
                        errorMessage = 'Format de fichier non supporté';
                        errorDetails = 'Le format de l\'image n\'est pas compatible avec Firebase Storage.';
                        errorType = 'format';
                        break;
                    case 'storage/canceled':
                        errorMessage = 'Upload annulé';
                        errorDetails = 'L\'upload a été annulé par l\'utilisateur.';
                        errorType = 'canceled';
                        break;
                    default:
                        errorMessage = `Erreur Firebase: ${error.message}`;
                        errorDetails = `Code d'erreur: ${error.code}`;
                }
            } else {
                errorMessage = `Erreur: ${error.message}`;
                errorDetails = 'Erreur inconnue lors de l\'upload Firebase.';
            }
            
            showUploadError(errorMessage, errorDetails, errorType);
        }
    }
    
    function showFirebaseSuccess(firebaseUrl) {
        // Supprimer les messages d'erreur existants
        const existingErrors = document.querySelectorAll('.upload-error-message');
        existingErrors.forEach(error => error.remove());
        
        // Afficher l'URL Firebase
        // Afficher l'URL Firebase
        // Afficher le lien Firebase
        
        // Afficher un message de succès
        const successDiv = document.createElement('div');
        successDiv.className = 'alert alert-success mt-2';
        successDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>
                    <strong>Upload Firebase réussi!</strong><br>
                    <small class="text-muted">L'image a été uploadée vers Firebase Storage.</small>
                </div>
            </div>
        `;
        
        const photoPreview = document.getElementById('photoPreview');
        photoPreview.appendChild(successDiv);
        
        // Supprimer le message après 5 secondes
        setTimeout(() => {
            if (successDiv.parentNode) {
                successDiv.remove();
            }
        }, 5000);
    }


    function showFirebaseLink(firebaseUrl) {
        if (firebaseUrl) {
            // Afficher l'URL Firebase
            // Afficher le lien Firebase
        }
    }

    function showUploadError(message, details = '', errorType = '') {
        // Supprimer les messages d'erreur existants
        const existingErrors = document.querySelectorAll('.upload-error-message');
        existingErrors.forEach(error => error.remove());
        
        // Déterminer le type d'alerte selon l'erreur
        let alertClass = 'alert-warning';
        let iconClass = 'fas fa-exclamation-triangle';
        
        switch(errorType) {
            case 'firebase_config':
            case 'permission':
                alertClass = 'alert-danger';
                iconClass = 'fas fa-ban';
                break;
            case 'network':
                alertClass = 'alert-info';
                iconClass = 'fas fa-wifi';
                break;
            case 'quota':
                alertClass = 'alert-danger';
                iconClass = 'fas fa-hdd';
                break;
            case 'format':
                alertClass = 'alert-warning';
                iconClass = 'fas fa-file-image';
                break;
        }
        
        // Créer le message d'erreur détaillé
        const errorDiv = document.createElement('div');
        errorDiv.className = `alert ${alertClass} upload-error-message mt-2`;
        errorDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <i class="${iconClass} me-2 mt-1"></i>
                <div>
                    <strong>${message}</strong>
                    ${details ? `<br><small class="text-muted">${details}</small>` : ''}
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="retryUpload()">
                            <i class="fas fa-redo me-1"></i> Réessayer
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="useLocalStorage()">
                            <i class="fas fa-save me-1"></i> Stocker localement
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Insérer le message d'erreur après l'aperçu
        const photoPreview = document.getElementById('photoPreview');
        photoPreview.appendChild(errorDiv);
        
        // Ne pas supprimer automatiquement - laisser l'utilisateur décider
    }
    
    // Fonction pour réessayer l'upload
    function retryUpload() {
        const fileInput = document.getElementById('profilePhotoInput');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            handleFile(file);
        }
    }
    
    // Fonction pour utiliser le stockage local
    function useLocalStorage() {
        const errorDiv = document.querySelector('.upload-error-message');
        if (errorDiv) {
            errorDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <div>
                        <strong>Image stockée localement</strong><br>
                        <small class="text-muted">L'image sera sauvegardée sur le serveur local.</small>
                    </div>
                </div>
            `;
            errorDiv.className = 'alert alert-success upload-error-message mt-2';
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
