<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - {{ $church->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, rgba(255, 38, 0, 0.1) 0%, rgba(0, 0, 0, 0.1) 100%),
                        url('{{ asset("images/auth-background.png") }}') center/cover no-repeat;
            min-height: 100vh;
            font-family: 'DM Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        
        .registration-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .registration-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            margin: 0 1rem;
            border: 2px solid #ff2600;
        }
        
        .church-header {
            background: linear-gradient(135deg, #ff2600 0%, #000000 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        
        .church-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .eglix-logo {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
        }
        
        .eglix-logo-img {
            height: 40px;
            width: auto;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .eglix-logo-img:hover {
            opacity: 1;
            transform: scale(1.05);
        }
        
        .church-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            border: 3px solid rgba(255,255,255,0.3);
            position: relative;
            z-index: 1;
        }
        
        .church-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
        }
        
        .church-description {
            opacity: 0.9;
            font-size: 1rem;
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
        }
        
        .form-section {
            padding: 2rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        .section-title {
            color: #000;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #ff2600;
            text-align: center;
            position: relative;
            font-family: 'DM Sans', sans-serif;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #000;
        }
        
        .form-label {
            font-weight: 600;
            color: #000;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'DM Sans', sans-serif;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: white;
            font-size: 0.95rem;
            font-family: 'DM Sans', sans-serif;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #ff2600;
            box-shadow: 0 0 0 0.2rem rgba(255, 38, 0, 0.25);
            outline: none;
        }
        
        .form-control:hover, .form-select:hover {
            border-color: #ff2600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 38, 0, 0.3);
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #cc1f00 0%, #ff2600 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(255, 38, 0, 0.3);
        }
        
        .photo-upload-area {
            border: 2px dashed #ff2600;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
            position: relative;
            overflow: hidden;
        }
        
        .photo-upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23ff2600" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.3;
        }
        
        .photo-upload-area:hover {
            border-color: #000;
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            transform: scale(1.02);
        }
        
        .photo-upload-area.dragover {
            border-color: #000;
            background: linear-gradient(135deg, #ffcccc 0%, #ff9999 100%);
            transform: scale(1.05);
        }
        
        .upload-button {
            background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 38, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .upload-button:hover {
            background: linear-gradient(135deg, #cc1f00 0%, #ff2600 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.4);
        }
        
        .photo-preview img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.3);
            border: 3px solid #ff2600;
        }
        
        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: #ffe6e6;
            border: 1px solid #ff2600;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(255, 38, 0, 0.3);
        }
        
        .required {
            color: #ff2600;
            font-weight: 700;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            color: #cc1f00;
            border-left: 4px solid #ff2600;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #e6ffe6 0%, #ccffcc 100%);
            color: #006600;
            border-left: 4px solid #00cc00;
        }
        
        .invalid-feedback {
            color: #ff2600;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #ff2600;
            box-shadow: 0 0 0 0.2rem rgba(255, 38, 0, 0.25);
        }
        
        .text-muted {
            color: #666 !important;
        }
        
        .upload-text {
            position: relative;
            z-index: 1;
        }
        
        .upload-formats {
            position: relative;
            z-index: 1;
        }
        
        @media (max-width: 768px) {
            .registration-card {
                margin: 0.5rem;
            }
            
            .church-header, .form-section {
                padding: 1.5rem;
            }
            
            .church-name {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-card">
            <!-- En-tête de l'église -->
            <div class="church-header">
                <!-- Logo Eglix -->
                <div class="eglix-logo">
                    <img src="{{ asset('images/eglix.png') }}" alt="Eglix" class="eglix-logo-img">
                </div>
                
                <div class="church-logo">
                    <i class="fas fa-church"></i>
                </div>
                <h1 class="church-name">{{ $church->name }}</h1>
                @if($church->description)
                    <p class="church-description">{{ $church->description }}</p>
                @endif
            </div>
            
            <!-- Formulaire d'inscription -->
            <div class="form-section">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <h3 class="section-title">
                    <i class="fas fa-user-plus me-2"></i>
                    Formulaire d'inscription
                </h3>
                
                <form method="POST" action="{{ route('members.public.store', $church_id) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Informations personnelles -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Prénom <span class="required">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                   value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="required">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                   value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                   value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informations détaillées -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Sexe</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Sélectionner</option>
                                <option value="male" @selected(old('gender')==='male')>Homme</option>
                                <option value="female" @selected(old('gender')==='female')>Femme</option>
                                <option value="other" @selected(old('gender')==='other')>Autre</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Situation matrimoniale</label>
                            <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                <option value="">Sélectionner</option>
                                <option value="single" @selected(old('marital_status')==='single')>Célibataire</option>
                                <option value="married" @selected(old('marital_status')==='married')>Marié(e)</option>
                                <option value="divorced" @selected(old('marital_status')==='divorced')>Divorcé(e)</option>
                                <option value="widowed" @selected(old('marital_status')==='widowed')>Veuf(ve)</option>
                            </select>
                            @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   value="{{ old('birth_date') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informations spirituelles -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Date de baptême</label>
                            <input type="date" name="baptized_at" class="form-control @error('baptized_at') is-invalid @enderror" 
                                   value="{{ old('baptized_at') }}">
                            @error('baptized_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Pasteur ou responsable du baptême</label>
                            <input type="text" name="baptism_responsible" class="form-control @error('baptism_responsible') is-invalid @enderror" 
                                   value="{{ old('baptism_responsible') }}" placeholder="Ex: Pasteur Jean Dupont">
                            @error('baptism_responsible')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date d'adhésion à l'église</label>
                            <input type="date" name="joined_at" class="form-control @error('joined_at') is-invalid @enderror" 
                                   value="{{ old('joined_at') }}">
                            @error('joined_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Photo de profil -->
                    <div class="mb-4">
                        <label class="form-label">Photo de profil</label>
                        <div class="photo-upload-area" id="photoUploadArea">
                            <div class="photo-upload-content" id="uploadContent">
                                <button type="button" class="upload-button" id="uploadButton">
                                    <i class="fas fa-camera me-2"></i>
                                    Ajouter une photo
                                </button>
                                <p class="mt-2 mb-0 text-muted">Ou glissez-déposez une image ici</p>
                                <small class="text-muted">JPG, PNG, WEBP • Max 4MB</small>
                            </div>
                            <div class="photo-preview" id="photoPreview" style="display: none;">
                                <img id="previewImage" src="" alt="Aperçu">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removePhoto">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="d-none @error('profile_photo') is-invalid @enderror">
                        <div class="upload-progress mt-2" id="uploadProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted mt-1 d-block" id="progressText">Upload en cours...</small>
                        </div>
                        @error('profile_photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label">Notes ou commentaires</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" 
                                  placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Boutons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check me-2"></i>
                            S'inscrire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('photoUploadArea');
            const fileInput = document.getElementById('profilePhotoInput');
            const uploadContent = document.getElementById('uploadContent');
            const photoPreview = document.getElementById('photoPreview');
            const previewImage = document.getElementById('previewImage');
            const removePhotoBtn = document.getElementById('removePhoto');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const uploadButton = document.getElementById('uploadButton');

            // Gestion du clic sur le bouton et la zone
            uploadButton.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });
            
            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            // Gestion du changement de fichier
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleFile(file);
                }
            });

            // Gestion du drag and drop
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
                        alert('Veuillez sélectionner un fichier image valide.');
                    }
                }
            });

            // Suppression de la photo
            removePhotoBtn.addEventListener('click', function() {
                fileInput.value = '';
                photoPreview.style.display = 'none';
                uploadContent.style.display = 'block';
                uploadProgress.style.display = 'none';
            });

            function handleFile(file) {
                // Validation du type de fichier
                if (!file.type.startsWith('image/')) {
                    alert('Veuillez sélectionner un fichier image valide.');
                    return;
                }

                // Validation de la taille (4MB max)
                if (file.size > 4 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximale : 4MB.');
                    return;
                }

                // Affichage de l'aperçu
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    photoPreview.style.display = 'block';
                    uploadContent.style.display = 'none';
                    
                    // Simulation de l'upload
                    simulateUpload();
                };
                reader.readAsDataURL(file);
            }

            function simulateUpload() {
                uploadProgress.style.display = 'block';
                let progress = 0;
                
                const interval = setInterval(function() {
                    progress += Math.random() * 15;
                    if (progress > 100) {
                        progress = 100;
                        clearInterval(interval);
                        setTimeout(function() {
                            uploadProgress.style.display = 'none';
                        }, 500);
                    }
                    progressBar.style.width = progress + '%';
                    progressText.textContent = `Upload: ${Math.round(progress)}%`;
                }, 200);
            }
        });
    </script>
</body>
</html>
