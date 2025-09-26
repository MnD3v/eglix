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

/* Design pour la sélection d'image */
.photo-upload-container {
    margin-bottom: 1rem;
}

.photo-upload-area {
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
    position: relative;
    overflow: hidden;
}

.photo-upload-area:hover {
    background: #f1f5f9;
    border-color: #FFCC00;
}

.photo-upload-area.dragover {
    background: #fef3c7;
    border-color: #FFCC00;
}

.upload-button {
    background: #FFCC00 !important;
    border: 2px solid #FFCC00 !important;
    border-radius: 12px !important;
    padding: 12px 24px !important;
    color: #1e293b !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    margin-bottom: 1rem !important;
    display: inline-block !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    box-shadow: 0 2px 4px rgba(255, 204, 0, 0.2) !important;
}

.upload-button:hover {
    background: #e6b800 !important;
    border-color: #e6b800 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(255, 204, 0, 0.3) !important;
}

.upload-text {
    margin-bottom: 0.5rem;
}

.upload-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.upload-subtitle {
    color: #64748b;
    margin: 0;
    font-size: 0.9rem;
}

.upload-formats {
    margin-top: 1rem;
}

.photo-upload-content {
    pointer-events: none;
    position: relative;
    z-index: 2;
}

/* Styles pour les images sélectionnées */
.selected-image-item {
    position: relative;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
}

.selected-image-item:hover {
    border-color: #FFCC00;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 204, 0, 0.15);
}

.selected-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

.remove-image-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(220, 38, 38, 0.9);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
}

.remove-image-btn:hover {
    background: rgba(185, 28, 28, 1);
    transform: scale(1.1);
}

.image-info {
    padding: 8px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
}

.image-name {
    font-size: 0.75rem;
    color: #374151;
    font-weight: 500;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.image-size {
    font-size: 0.7rem;
    color: #6b7280;
    margin: 0;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
    
    .photo-upload-area {
        padding: 1.5rem 1rem;
    }
    
    .upload-button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
    
    .selected-image {
        height: 100px;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouvelle Entrée -->
    <div class="appbar journal-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('journal.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Entrée</h1>
                </div>
            </div>
        </div>
    </div>

    <form id="journalForm" action="{{ route('journal.store') }}" method="POST" style="position:relative;">
        @csrf
        
        <!-- Section Informations de Base -->
        <div class="form-section">
            <h2 class="section-title">Informations de Base</h2>
            <p class="section-subtitle">Détails principaux de l'entrée du journal</p>
            
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <label class="form-label">Titre</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}" placeholder="Ex: Célébration de Pâques, Réunion du conseil...">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Catégorie</label>
                    <select name="category" class="form-select" required>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" @selected(old('category')===$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="occurred_at" class="form-control" required value="{{ old('occurred_at', now()->format('Y-m-d')) }}">
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="form-section">
            <h2 class="section-title">Description</h2>
            <p class="section-subtitle">Détails sur l'événement ou l'activité</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="6" class="form-control" placeholder="Décrivez l'événement, les participants, les moments importants...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section Images -->
        <div class="form-section">
            <h2 class="section-title">Images</h2>
            <p class="section-subtitle">Ajoutez des photos pour illustrer cette entrée</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Images</label>
                    <div class="photo-upload-container">
                        <div class="photo-upload-area" id="journalUploadArea" role="button" tabindex="0" aria-label="Zone de téléversement d'images - Cliquez ou glissez-déposez vos images ici">
                            <div class="photo-upload-content">
                                <button type="button" class="upload-button" id="journalUploadButton">
                                    <i class="bi bi-cloud-upload me-2"></i>Sélectionner des images
                                </button>
                                <div class="upload-text">
                                    <p class="upload-title">Ou déposez vos images ici</p>
                                </div>
                                <div class="upload-formats">
                                    <small class="text-muted">JPG, PNG, WEBP • Max 4MB par image</small>
                                </div>
                            </div>
                        </div>
                        <input id="imagesInput" type="file" accept="image/*" multiple class="form-control d-none" aria-label="Sélectionner des images">
                    </div>
                    
                    <!-- Zone d'affichage des images sélectionnées -->
                    <div id="selectedImagesContainer" class="mt-3" style="display: none;">
                        <h6 class="mb-2">Images sélectionnées :</h6>
                        <div id="selectedImagesGrid" class="row g-2">
                            <!-- Les images sélectionnées seront affichées ici -->
                        </div>
                    </div>
                    
                    <div class="form-text">
                        Les fichiers sont envoyés vers Firebase Storage ; seules les URLs sont stockées en base.
                    </div>

                    <div id="uploadProgress" class="small text-muted mt-2"></div>
                    <div class="progress mt-2 d-none" id="uploadBarWrap" style="height: 12px; border-radius: 15px; background-color: #ffffff; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="progress-bar" id="uploadBar" style="width: 0%; border-radius: 15px; background: linear-gradient(90deg, #28a745, #20c997); transition: width 0.2s ease; box-shadow: 0 2px 4px rgba(40,167,69,0.3);"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1 d-none" id="uploadInfo">
                        <small class="text-muted">
                            <i class="bi bi-cloud-upload me-1"></i>
                            <span id="uploadStatus">Préparation...</span>
                        </small>
                        <small class="text-success fw-bold" id="uploadPercent">0%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-3 justify-content-end mt-4">
            <button class="btn btn-primary" id="submitBtn">
                <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer l'Entrée
            </button>
        </div>

        <!-- Overlay loader -->
        <div id="uploadOverlay" class="d-none" style="position:absolute; inset:0; background:rgba(255,255,255,.7); display:flex; align-items:center; justify-content:center; border-radius:12px;">
            <div class="text-center">
                <div class="spinner-border text-custom" role="status" aria-hidden="true"></div>
                <div class="mt-2 small text-muted">Téléversement en cours...</div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Firebase v9 (modular) --}}
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getStorage, ref, uploadBytesResumable, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-storage.js";

// Configuration Firebase exacte depuis google-services.json
const firebaseConfig = {
  apiKey: "AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk",
  authDomain: "xboite-d7c80.firebaseapp.com",
  projectId: "xboite-d7c80",
  storageBucket: "xboite-d7c80.firebasestorage.app",
  messagingSenderId: "457797490593",
  appId: "1:457797490593:web:eglix-web"
};

// Vérifier si la configuration Firebase est valide
function isFirebaseConfigValid() {
  // Vérifier que toutes les valeurs sont présentes et non factices
  const isValid = firebaseConfig.apiKey && 
         firebaseConfig.apiKey !== 'xxxx' && 
         firebaseConfig.apiKey.length > 20 && // Les clés API Firebase font généralement plus de 20 caractères
         firebaseConfig.projectId && 
         firebaseConfig.projectId !== 'xxxx' &&
         firebaseConfig.storageBucket && 
         firebaseConfig.storageBucket !== 'xxxx.appspot.com' &&
         firebaseConfig.messagingSenderId &&
         firebaseConfig.messagingSenderId !== '123456789' &&
         firebaseConfig.authDomain &&
         firebaseConfig.authDomain !== 'xxxx.firebaseapp.com';
  
  console.log('Validation Firebase:', {
    isValid: isValid,
    config: firebaseConfig,
    checks: {
      apiKey: firebaseConfig.apiKey && firebaseConfig.apiKey !== 'xxxx' && firebaseConfig.apiKey.length > 20,
      projectId: firebaseConfig.projectId && firebaseConfig.projectId !== 'xxxx',
      storageBucket: firebaseConfig.storageBucket && firebaseConfig.storageBucket !== 'xxxx.appspot.com',
      messagingSenderId: firebaseConfig.messagingSenderId && firebaseConfig.messagingSenderId !== '123456789',
      authDomain: firebaseConfig.authDomain && firebaseConfig.authDomain !== 'xxxx.firebaseapp.com'
    }
  });
  
  return isValid;
}

// Configuration Firebase valide - procéder directement
console.log('Configuration Firebase chargée:', firebaseConfig);
window.useLocalStorage = false;

const app = initializeApp(firebaseConfig);
const storage = getStorage(app);

document.addEventListener('DOMContentLoaded', () => {
  const input     = document.getElementById('imagesInput');
  const form      = document.getElementById('journalForm');
  const progress  = document.getElementById('uploadProgress');
  const overlay   = document.getElementById('uploadOverlay');
  const barWrap   = document.getElementById('uploadBarWrap');
  const bar       = document.getElementById('uploadBar');
  const submitBtn = document.getElementById('submitBtn');
  const uploadInfo = document.getElementById('uploadInfo');
  const uploadStatus = document.getElementById('uploadStatus');
  const uploadPercent = document.getElementById('uploadPercent');
  const uploadArea = document.getElementById('journalUploadArea');

  // Gestion du bouton et de la zone de clic pour le journal
  const journalUploadButton = document.getElementById('journalUploadButton');
  
  journalUploadButton.addEventListener('click', (e) => {
    e.stopPropagation();
    input.click();
  });
  
  uploadArea.addEventListener('click', () => {
    input.click();
  });

  // Gestion des interactions clavier pour l'accessibilité
  uploadArea.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      input.click();
    }
  });

  // Gestion du drag & drop
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
  });

  uploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
  });

  // Fonction pour afficher les images sélectionnées
  function displaySelectedImages(files) {
    const container = document.getElementById('selectedImagesContainer');
    const grid = document.getElementById('selectedImagesGrid');
    
    // Vider la grille existante
    grid.innerHTML = '';
    
    // Afficher chaque image
    files.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const imageItem = document.createElement('div');
        imageItem.className = 'col-md-3 col-sm-4 col-6';
        imageItem.innerHTML = `
          <div class="selected-image-item">
            <img src="${e.target.result}" alt="${file.name}" class="selected-image">
            <button type="button" class="remove-image-btn" onclick="removeImage(${index})" title="Supprimer cette image">
              <i class="fas fa-times"></i>
            </button>
            <div class="image-info">
              <p class="image-name">${file.name}</p>
              <p class="image-size">${formatFileSize(file.size)}</p>
            </div>
          </div>
        `;
        grid.appendChild(imageItem);
      };
      reader.readAsDataURL(file);
    });
    
    // Afficher le conteneur
    container.style.display = 'block';
  }
  
  // Fonction pour supprimer une image
  function removeImage(index) {
    const files = Array.from(input.files);
    files.splice(index, 1);
    
    // Créer un nouveau FileList
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;
    
    // Réafficher les images restantes
    if (files.length > 0) {
      displaySelectedImages(files);
    } else {
      document.getElementById('selectedImagesContainer').style.display = 'none';
    }
  }
  
  // Fonction pour formater la taille des fichiers
  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }
  
  // Gestion du changement de fichiers
  input.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    if (files.length > 0) {
      displaySelectedImages(files);
    } else {
      document.getElementById('selectedImagesContainer').style.display = 'none';
    }
  });

  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    if (files.length > 0) {
      input.files = e.dataTransfer.files;
      // Déclencher l'événement change pour traiter les fichiers
      input.dispatchEvent(new Event('change'));
    }
  });

  form.addEventListener('submit', async (e) => {
    // Si aucun fichier, on poste directement
    if (!input.files || input.files.length === 0) return;

    e.preventDefault();
    const files = Array.from(input.files);
    const urls  = [];

    // UI: état chargement
    overlay.classList.remove('d-none');
    barWrap.classList.remove('d-none');
    uploadInfo.classList.remove('d-none');
    submitBtn.disabled = true;
    progress.textContent = 'Préparation des fichiers...';
    uploadStatus.textContent = 'Préparation des fichiers...';
    uploadPercent.textContent = '0%';

    // Calcul progress global = somme des bytes transférés / somme des bytes totaux
    const totalBytes = files.reduce((sum, f) => sum + f.size, 0);
    let transferred  = 0;

    try {
      for (let i = 0; i < files.length; i++) {
        const file   = files[i];
        const safe   = file.name.replace(/[^a-zA-Z0-9_.-]/g, '_');
        const path   = `journal/${Date.now()}_${i}_${safe}`;
        const storageRef = ref(storage, path);
        const task = uploadBytesResumable(storageRef, file);

        await new Promise((resolve, reject) => {
          task.on('state_changed', (snap) => {
            // met à jour la barre de progression globale
            const delta = snap.bytesTransferred - (task._lastTransferred || 0);
            task._lastTransferred = snap.bytesTransferred;
            transferred += Math.max(0, delta);
            const pct = Math.round((transferred / totalBytes) * 100);
            bar.style.width = pct + '%';
            uploadPercent.textContent = pct + '%';
            
            if (pct < 30) {
              uploadStatus.textContent = 'Préparation...';
            } else if (pct < 70) {
              uploadStatus.textContent = 'Upload vers Firebase...';
            } else if (pct < 100) {
              uploadStatus.textContent = 'Finalisation...';
            } else {
              uploadStatus.textContent = 'Terminé!';
            }
            
            progress.textContent = `Téléversement ${pct}% (${i+1}/${files.length})`;
          }, (error) => {
            // Gestion détaillée des erreurs Firebase
            console.error('Erreur Firebase:', error);
            let errorMessage = 'Erreur de téléversement';
            
            switch (error.code) {
              case 'storage/unauthorized':
                errorMessage = 'Accès non autorisé. Vérifiez la configuration Firebase.';
                break;
              case 'storage/canceled':
                errorMessage = 'Téléversement annulé par l\'utilisateur.';
                break;
              case 'storage/unknown':
                errorMessage = 'Erreur inconnue du serveur Firebase.';
                break;
              case 'storage/invalid-format':
                errorMessage = 'Format de fichier non supporté.';
                break;
              case 'storage/invalid-checksum':
                errorMessage = 'Fichier corrompu détecté.';
                break;
              case 'storage/quota-exceeded':
                errorMessage = 'Quota de stockage Firebase dépassé.';
                break;
              case 'storage/unauthenticated':
                errorMessage = 'Authentification Firebase requise.';
                break;
              case 'storage/retry-limit-exceeded':
                errorMessage = 'Nombre maximum de tentatives dépassé.';
                break;
              default:
                errorMessage = `Erreur Firebase: ${error.message || error.code}`;
            }
            
            reject(new Error(`${errorMessage} (Fichier: ${file.name})`));
          }, async () => {
            try {
              const url = await getDownloadURL(task.snapshot.ref);
              urls.push(url);
              resolve();
            } catch (urlError) {
              console.error('Erreur lors de la récupération de l\'URL:', urlError);
              reject(new Error(`Impossible de récupérer l'URL de téléchargement pour ${file.name}`));
            }
          });
        });
      }

      // injecte les URL dans le formulaire
      urls.forEach((url) => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'image_urls[]';
        hidden.value = url;
        form.appendChild(hidden);
      });

      // Nettoie l'input file (on ne veut pas envoyer les binaires au serveur)
      input.value = '';

      // Envoie final vers Laravel
      form.submit();
    } catch (err) {
      console.error('Erreur détaillée:', err);
      
      // Affichage d'erreur amélioré avec détails
      let errorMessage = 'Erreur de téléversement';
      let errorDetails = '';
      
      if (err.message) {
        errorMessage = err.message;
      } else if (err.code) {
        errorMessage = `Erreur ${err.code}`;
      }
      
      // Ajouter des détails techniques pour le débogage
      if (err.stack) {
        errorDetails = `<br><small class="text-muted">Détails techniques: ${err.stack.split('\n')[0]}</small>`;
      }
      
      // Afficher l'erreur avec un style d'alerte Bootstrap
      progress.innerHTML = `
        <div class="alert alert-danger mt-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Échec du téléversement</strong><br>
          ${errorMessage}${errorDetails}
        </div>
      `;
      
      // Réinitialiser l'interface
      overlay.classList.add('d-none');
      barWrap.classList.add('d-none');
      uploadInfo.classList.add('d-none');
      submitBtn.disabled = false;
      
      // Permettre à l'utilisateur de réessayer
      setTimeout(() => {
        progress.innerHTML = '<div class="text-muted">Prêt pour un nouveau téléversement...</div>';
      }, 5000);
    }
  });

  // Fonction pour gérer l'upload local
  async function handleLocalUpload(files, urls) {
    try {
      progress.textContent = 'Configuration Firebase non disponible, utilisation du stockage local...';
      
      // Simuler l'upload local avec progression
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const progressPercent = Math.round(((i + 1) / files.length) * 100);
        
        bar.style.width = progressPercent + '%';
        uploadPercent.textContent = progressPercent + '%';
        uploadStatus.textContent = `Préparation locale (${i+1}/${files.length})`;
        progress.textContent = `Préparation locale ${progressPercent}% (${i+1}/${files.length})`;
        
        // Simuler un délai d'upload
        await new Promise(resolve => setTimeout(resolve, 200));
        
        // Créer une URL locale simulée
        const localUrl = `local://journal/${Date.now()}_${i}_${file.name}`;
        urls.push(localUrl);
      }
      
      // Injecter les URLs dans le formulaire
      urls.forEach((url) => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'image_urls[]';
        hidden.value = url;
        form.appendChild(hidden);
      });
      
      // Nettoyer l'input file
      input.value = '';
      
      // Afficher un message d'information
      progress.innerHTML = `
        <div class="alert alert-info mt-2">
          <i class="fas fa-info-circle me-2"></i>
          <strong>Stockage local activé</strong><br>
          Les images seront stockées sur le serveur local car Firebase n'est pas configuré.
        </div>
      `;
      
      // Envoyer le formulaire
      setTimeout(() => {
        form.submit();
      }, 1000);
      
    } catch (err) {
      console.error('Erreur upload local:', err);
      progress.innerHTML = `
        <div class="alert alert-danger mt-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Erreur de stockage local</strong><br>
          ${err.message || 'Erreur inconnue'}
        </div>
      `;
      overlay.classList.add('d-none');
      submitBtn.disabled = false;
    }
  }
});
</script>
@endpush
