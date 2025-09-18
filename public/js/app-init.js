(function(){
  if (!window.firebase || window.fbInitialized) return;
  try {
    const firebaseConfig = {
      apiKey: 'AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk',
      projectId: 'xboite-d7c80',
      storageBucket: 'xboite-d7c80.appspot.com'
    };
    firebase.initializeApp(firebaseConfig);
    window.fbStorage = firebase.storage();
    window.fbInitialized = true;
  } catch (e) {
    console.warn('Firebase init skipped', e);
  }
})();


