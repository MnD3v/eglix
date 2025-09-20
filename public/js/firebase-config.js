// Configuration Firebase pour l'application web Eglix
// Basée sur le fichier google-services.json fourni

const firebaseConfig = {
  apiKey: "AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk",
  authDomain: "xboite-d7c80.firebaseapp.com",
  projectId: "xboite-d7c80",
  storageBucket: "xboite-d7c80.firebasestorage.app",
  messagingSenderId: "457797490593",
  appId: "1:457797490593:web:eglix-web-app"
};

// Initialisation Firebase
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getStorage } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-storage.js";

const app = initializeApp(firebaseConfig);
const storage = getStorage(app);

export { storage };
