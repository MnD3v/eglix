# 🔥 Instructions de Configuration Firebase

## 📋 Variables à Ajouter au Fichier .env

Ajoutez ces lignes à votre fichier `.env` principal :

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

## 🔧 Étapes de Configuration

### 1. **Ajouter les Variables**
- Ouvrez votre fichier `.env` principal
- Ajoutez les variables Firebase ci-dessus
- Sauvegardez le fichier

### 2. **Redémarrer le Serveur**
```bash
# Arrêter le serveur actuel (Ctrl+C)
# Puis relancer :
php artisan serve --host=0.0.0.0 --port=8000
```

### 3. **Tester la Configuration**
- Allez sur : `http://localhost:8000/firebase-test`
- Vérifiez que toutes les valeurs sont correctes
- Testez l'upload d'une image

## ✅ Résultat Attendu

Après configuration, vous devriez voir :
- ✅ Configuration Firebase valide
- ✅ Test d'upload réussi
- ✅ Plus d'erreurs simulées

## 🐛 Dépannage

Si vous obtenez encore des erreurs :
1. Vérifiez que le fichier `.env` est bien sauvegardé
2. Redémarrez le serveur Laravel
3. Vérifiez la console du navigateur pour les logs
4. Testez sur `/firebase-test` pour diagnostiquer
