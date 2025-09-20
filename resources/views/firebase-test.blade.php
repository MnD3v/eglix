{{-- resources/views/firebase-test.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Configuration Firebase - Eglix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">🔥 Test Configuration Firebase</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>État de la Configuration</h5>
                    </div>
                    <div class="card-body">
                        @if($isConfigured)
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Firebase est correctement configuré</strong>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Firebase n'est pas configuré</strong>
                                @if($error)
                                    <br><small>{{ $error }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Configuration JavaScript</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3"><code>{{ json_encode($config, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Test d'Upload Firebase</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="testFile" class="form-label">Sélectionnez une image pour tester l'upload Firebase</label>
                            <input type="file" id="testFile" class="form-control" accept="image/*">
                        </div>
                        
                        <div id="uploadResult" class="mt-3"></div>
                        <div id="uploadProgress" class="mt-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('journal.index') }}" class="btn btn-primary">Retour au Journal</a>
            <button onclick="testFirebaseConfig()" class="btn btn-info">Tester la Configuration</button>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
        import { getStorage, ref, uploadBytesResumable, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-storage.js";

        // Configuration Firebase depuis Laravel
        const firebaseConfig = @json($config);
        
        console.log('Configuration Firebase:', firebaseConfig);
        
        try {
            const app = initializeApp(firebaseConfig);
            const storage = getStorage(app);
            
            console.log('Firebase initialisé avec succès');
            
            // Test d'upload
            document.getElementById('testFile').addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const resultDiv = document.getElementById('uploadResult');
                const progressDiv = document.getElementById('uploadProgress');
                const progressBar = progressDiv.querySelector('.progress-bar');
                
                try {
                    progressDiv.style.display = 'block';
                    resultDiv.innerHTML = '<div class="alert alert-info">Upload en cours...</div>';
                    
                    const fileName = `test/${Date.now()}_${file.name}`;
                    const storageRef = ref(storage, fileName);
                    const uploadTask = uploadBytesResumable(storageRef, file);
                    
                    uploadTask.on('state_changed', 
                        (snapshot) => {
                            const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                            progressBar.style.width = progress + '%';
                        },
                        (error) => {
                            console.error('Erreur upload:', error);
                            resultDiv.innerHTML = `
                                <div class="alert alert-danger">
                                    <strong>Erreur d'upload:</strong><br>
                                    ${error.message}
                                </div>
                            `;
                            progressDiv.style.display = 'none';
                        },
                        async () => {
                            try {
                                const downloadURL = await getDownloadURL(uploadTask.snapshot.ref);
                                resultDiv.innerHTML = `
                                    <div class="alert alert-success">
                                        <strong>Upload réussi!</strong><br>
                                        <a href="${downloadURL}" target="_blank">Voir l'image</a>
                                    </div>
                                `;
                                progressDiv.style.display = 'none';
                            } catch (urlError) {
                                console.error('Erreur URL:', urlError);
                                resultDiv.innerHTML = `
                                    <div class="alert alert-warning">
                                        <strong>Upload terminé mais erreur URL:</strong><br>
                                        ${urlError.message}
                                    </div>
                                `;
                                progressDiv.style.display = 'none';
                            }
                        }
                    );
                    
                } catch (error) {
                    console.error('Erreur:', error);
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>Erreur:</strong><br>
                            ${error.message}
                        </div>
                    `;
                    progressDiv.style.display = 'none';
                }
            });
            
        } catch (error) {
            console.error('Erreur initialisation Firebase:', error);
            document.getElementById('uploadResult').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Erreur d'initialisation Firebase:</strong><br>
                    ${error.message}
                </div>
            `;
        }
        
        // Fonction de test globale
        window.testFirebaseConfig = function() {
            console.log('Test de configuration Firebase...');
            console.log('Config:', firebaseConfig);
            
            // Vérifier chaque paramètre
            const checks = {
                apiKey: firebaseConfig.apiKey && firebaseConfig.apiKey.length > 20,
                projectId: firebaseConfig.projectId && firebaseConfig.projectId !== 'xxxx',
                storageBucket: firebaseConfig.storageBucket && firebaseConfig.storageBucket.includes('firebasestorage'),
                authDomain: firebaseConfig.authDomain && firebaseConfig.authDomain.includes('firebaseapp'),
                messagingSenderId: firebaseConfig.messagingSenderId && firebaseConfig.messagingSenderId.length > 5,
                appId: firebaseConfig.appId && firebaseConfig.appId.includes(':')
            };
            
            console.log('Vérifications:', checks);
            
            const allValid = Object.values(checks).every(check => check === true);
            
            if (allValid) {
                alert('✅ Configuration Firebase valide!');
            } else {
                alert('❌ Configuration Firebase invalide. Vérifiez la console pour plus de détails.');
            }
        };
    </script>
</body>
</html>
