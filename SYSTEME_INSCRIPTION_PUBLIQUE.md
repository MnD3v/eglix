# Système d'Inscription Publique des Membres

## Vue d'ensemble

Le système d'inscription publique permet aux membres de s'inscrire directement dans une église sans avoir besoin de se connecter au système d'administration. Chaque église peut partager un lien unique permettant aux nouveaux membres de s'inscrire.

## Fonctionnalités

- ✅ Inscription publique sans authentification
- ✅ Formulaire moderne et responsive
- ✅ Upload de photo de profil avec Firebase Storage
- ✅ Validation complète des données
- ✅ Page de confirmation après inscription
- ✅ Sécurité : vérification de l'état actif de l'église

## URLs du Système

### 1. Formulaire d'inscription publique
```
http://127.0.0.1:8000/members/create/{church_id}
```

**Exemple :**
```
http://127.0.0.1:8000/members/create/4
```

### 2. Page de succès après inscription
```
http://127.0.0.1:8000/members/success/{church_slug}
```

**Exemple :**
```
http://127.0.0.1:8000/members/success/ad-dongoyo
```

## Églises Disponibles

Voici les églises actuellement disponibles dans la base de données :

| ID | Nom | Slug | Statut |
|----|-----|------|--------|
| 4 | AD - Dongoyo | ad-dongoyo | ✅ Actif |
| 5 | Adventiste Dongoyo | adventiste-dongoyo | ✅ Actif |
| 6 | AD - Dongoyo | ad-dongoyo-1 | ✅ Actif |
| 7 | DA Bohou | da-bohou | ✅ Actif |
| 8 | Eglise Dongoyo | eglise-dongoyo | ✅ Actif |
| 11 | Eglise Dongoyo | eglise-dongoyo-1 | ✅ Actif |
| 12 | Catholique | catholique | ✅ Actif |

## Exemples de Liens d'Inscription

### Pour l'église "AD - Dongoyo" (ID: 4)
```
http://127.0.0.1:8000/members/create/4
```

### Pour l'église "Adventiste Dongoyo" (ID: 5)
```
http://127.0.0.1:8000/members/create/5
```

### Pour l'église "DA Bohou" (ID: 7)
```
http://127.0.0.1:8000/members/create/7
```

### Pour l'église "Catholique" (ID: 12)
```
http://127.0.0.1:8000/members/create/12
```

## Comment Utiliser

### 1. Pour les Administrateurs d'Église
1. Connectez-vous à votre compte administrateur
2. Allez dans la section "Membres"
3. Utilisez le lien d'inscription publique correspondant à votre église
4. Partagez ce lien avec vos membres potentiels

### 2. Pour les Nouveaux Membres
1. Cliquez sur le lien d'inscription fourni par votre église
2. Remplissez le formulaire d'inscription
3. Ajoutez une photo de profil (optionnel)
4. Soumettez le formulaire
5. Recevez une confirmation d'inscription

## Champs du Formulaire

### Informations Obligatoires
- **Prénom** : Nom de famille du membre
- **Nom** : Nom de famille du membre

### Informations Optionnelles
- **Email** : Adresse email de contact
- **Téléphone** : Numéro de téléphone
- **Adresse** : Adresse physique complète
- **Sexe** : Homme, Femme, Autre
- **Situation matrimoniale** : Célibataire, Marié(e), Divorcé(e), Veuf(ve)
- **Date de naissance** : Date de naissance
- **Date de baptême** : Date de baptême dans l'église
- **Pasteur responsable** : Nom du pasteur qui a baptisé
- **Date d'adhésion** : Date d'adhésion à l'église
- **Photo de profil** : Image de profil (JPG, PNG, WEBP, max 4MB)
- **Notes** : Commentaires ou informations supplémentaires

## Sécurité

- ✅ Vérification de l'existence de l'église
- ✅ Vérification du statut actif de l'église
- ✅ Validation des données côté serveur
- ✅ Protection CSRF
- ✅ Upload sécurisé des images vers Firebase Storage
- ✅ Validation des types de fichiers
- ✅ Limitation de la taille des fichiers

## Personnalisation

### Modifier l'Apparence
Les fichiers de vue se trouvent dans :
- `resources/views/members/public-create.blade.php` - Formulaire d'inscription
- `resources/views/members/public-success.blade.php` - Page de succès

### Modifier la Logique
Le contrôleur se trouve dans :
- `app/Http/Controllers/MemberController.php` - Méthodes `showPublicRegistrationForm()` et `processPublicRegistration()`

### Modifier les Routes
Les routes publiques se trouvent dans :
- `routes/web.php` - Routes `members.public.*`

## Dépannage

### Problème : "Cette église n'est pas active"
- Vérifiez que l'église existe dans la base de données
- Vérifiez que le champ `is_active` est à `true`

### Problème : "Lien invalide"
- Vérifiez que l'ID de l'église dans l'URL est correct
- Vérifiez que l'église existe dans la base de données

### Problème : Upload d'image
- Vérifiez la configuration Firebase Storage
- Vérifiez les permissions de stockage
- Vérifiez la taille du fichier (max 4MB)

## Support

Pour toute question ou problème, contactez l'équipe de développement.
