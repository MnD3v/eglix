(function(){
  // Désactiver l'ancienne configuration Firebase pour éviter les conflits
  if (window.firebase || window.fbInitialized) {
    console.log('Firebase déjà initialisé, configuration ignorée');
    return;
  }
  
  try {
    // Configuration Firebase simplifiée (sera remplacée par la configuration dynamique)
    const firebaseConfig = {
      apiKey: 'AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk',
      projectId: 'xboite-d7c80',
      storageBucket: 'xboite-d7c80.firebasestorage.app' // Correction du bucket
    };
    
    // Ne pas initialiser Firebase ici, laisser les modules le faire
    console.log('Configuration Firebase disponible:', firebaseConfig);
    window.firebaseConfig = firebaseConfig;
    
  } catch (e) {
    console.warn('Configuration Firebase ignorée:', e);
  }
})();


