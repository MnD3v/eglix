# 🔥 Configuration Firebase pour Eglix

## 📋 Informations du Projet

Basé sur votre fichier `google-services.json`, voici les informations de configuration :

### **Données du Projet**
- **Project ID** : `xboite-d7c80`
- **Project Number** : `457797490593`
- **Storage Bucket** : `xboite-d7c80.firebasestorage.app`
- **API Key** : `AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk`

## ⚙️ Configuration Requise

### **1. Variables d'Environnement (.env)**

Ajoutez ces lignes à votre fichier `.env` :

```env
# Configuration Firebase
FIREBASE_PROJECT_ID=xboite-d7c80
FIREBASE_STORAGE_BUCKET=xboite-d7c80.firebasestorage.app
FIREBASE_API_KEY=AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk
FIREBASE_AUTH_DOMAIN=xboite-d7c80.firebaseapp.com
FIREBASE_MESSAGING_SENDER_ID=457797490593
FIREBASE_APP_ID=1:457797490593:web:eglix-web-app

# URLs Firebase
FIREBASE_STORAGE_URL=https://firebasestorage.googleapis.com/v0/b/xboite-d7c80.firebasestorage.app/o/
FIREBASE_UPLOAD_URL=https://firebasestorage.googleapis.com/v0/b/xboite-d7c80.firebasestorage.app/o/
```

### **2. Règles de Sécurité Firebase Storage**

Dans la console Firebase, configurez ces règles de sécurité :

```javascript
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    // Permettre la lecture à tous
    match /{allPaths=**} {
      allow read: if true;
    }
    
    // Permettre l'écriture pour les uploads d'images
    match /journal/{allPaths=**} {
      allow write: if true; // À sécuriser en production
    }
    
    match /member_photos/{allPaths=**} {
      allow write: if true; // À sécuriser en production
    }
  }
}
```

### **3. Configuration des Domaines Autorisés**

Dans la console Firebase :
1. Allez dans **Authentication** > **Settings** > **Authorized domains**
2. Ajoutez votre domaine (ex: `votre-domaine.com`)
3. Pour le développement local, ajoutez `localhost`

## 🚀 Test de la Configuration

### **Vérification Côté Serveur**
```bash
php artisan tinker
>>> config('firebase.project_id')
>>> config('firebase.api_key')
```

### **Vérification Côté Client**
Ouvrez la console du navigateur et vérifiez :
```javascript
console.log('Firebase config:', firebaseConfig);
```

## 🔒 Sécurité en Production

### **Règles de Sécurité Recommandées**
```javascript
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    match /{allPaths=**} {
      allow read: if true;
      allow write: if request.auth != null; // Authentification requise
    }
  }
}
```

### **Variables d'Environnement Sécurisées**
- Ne jamais commiter le fichier `.env` avec les vraies clés
- Utiliser des variables d'environnement sur le serveur de production
- Régénérer les clés API si nécessaire

## 🐛 Dépannage

### **Erreur "Permissions insuffisantes"**
1. Vérifiez que les règles de sécurité permettent l'écriture
2. Vérifiez que le domaine est autorisé
3. Vérifiez que l'API Key est correcte

### **Erreur "Configuration Firebase invalide"**
1. Vérifiez les variables d'environnement
2. Redémarrez le serveur après modification du `.env`
3. Vérifiez la console pour les détails de configuration

## 📱 Applications Mobiles

Votre fichier `google-services.json` contient des configurations pour plusieurs applications Android :
- `com.equilibre.blanksplit`
- `com.equilibre.eboite`
- `com.equilibre.sboite`
- `com.equilibre.sboite_admin`
- `com.equilibre.xboite`

Ces applications peuvent utiliser la même configuration Firebase que l'application web.
