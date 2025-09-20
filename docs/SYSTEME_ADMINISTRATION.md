# 🛡️ Système d'Administration Eglix

## 📋 Vue d'ensemble

Le système d'administration Eglix permet la gestion globale de toutes les églises inscrites sur la plateforme, leurs abonnements et l'affectation des abonnements.

## 🔗 Accès

- **Route principale** : `/admin-0202`
- **Accès** : Réservé aux super administrateurs (`is_super_admin = true`)
- **Navigation** : Lien "Administration" dans la sidebar (visible uniquement pour les super admins)

## 🏗️ Architecture

### Contrôleur
- **AdminController** : Gestion complète de l'administration
- **Méthodes principales** :
  - `index()` : Tableau de bord avec toutes les églises
  - `showChurch()` : Détails d'une église spécifique
  - `createSubscription()` : Créer un abonnement pour une église
  - `storeSubscription()` : Enregistrer un nouvel abonnement
  - `markSubscriptionPaid()` : Marquer un abonnement comme payé
  - `suspendSubscription()` : Suspendre un abonnement
  - `renewSubscription()` : Renouveler un abonnement
  - `exportChurches()` : Exporter les données en CSV

### Modèles
- **Church** : Relation `subscriptions()` ajoutée
- **Subscription** : Modèle adapté pour l'accès plateforme par église
- **User** : Champ `is_super_admin` ajouté

## 📊 Fonctionnalités

### 1. Tableau de bord global
- **Statistiques** :
  - Nombre total d'églises
  - Abonnements actifs/expirés
  - Paiements en attente
  - Revenus totaux
  - Églises sans abonnement

- **Filtres** :
  - Recherche par nom, adresse, téléphone, email
  - Filtre par statut d'abonnement (actif, expiré, en attente, sans abonnement)

- **Actions rapides** :
  - Voir les détails d'une église
  - Créer un abonnement
  - Marquer comme payé
  - Renouveler un abonnement

### 2. Gestion des églises
- **Informations complètes** :
  - Détails de l'église (nom, adresse, téléphone, email)
  - Date d'inscription
  - Nombre d'utilisateurs
  - Statut (actif/inactif)

- **Historique des abonnements** :
  - Tous les abonnements passés et actuels
  - Détails complets (plan, montant, dates, statut)
  - Actions disponibles selon le statut

### 3. Gestion des abonnements
- **Création d'abonnement** :
  - Sélection de plan (Basique, Premium, Entreprise)
  - Configuration personnalisée (montant, limite membres)
  - Fonctionnalités (rapports avancés, API)
  - Dates de début et fin
  - Informations de paiement

- **Actions sur les abonnements** :
  - Marquer comme payé
  - Suspendre
  - Renouveler
  - Voir l'historique complet

### 4. Export des données
- **Format CSV** avec toutes les informations :
  - Détails de l'église
  - Plan actuel et montant
  - Date d'expiration
  - Statut de l'abonnement
  - Nombre d'utilisateurs

## 🎨 Interface utilisateur

### Design moderne
- **AppBar personnalisé** : Titre, sous-titre, actions
- **Cartes interactives** : Hover effects, transitions fluides
- **Statuts visuels** : Badges colorés pour les statuts
- **Responsive** : Adaptation mobile et desktop

### Couleurs et statuts
- **Vert** : Abonnements actifs
- **Rouge** : Abonnements expirés
- **Jaune** : Paiements en attente
- **Gris** : Sans abonnement

## 🔐 Sécurité

### Contrôle d'accès
- **Super admin uniquement** : Vérification `is_super_admin = true`
- **Isolation des données** : Chaque église ne voit que ses propres données
- **Validation** : Toutes les entrées sont validées côté serveur

### Permissions
- **Lecture** : Voir toutes les églises et abonnements
- **Écriture** : Créer, modifier, suspendre les abonnements
- **Export** : Télécharger les données en CSV

## 📱 Responsive Design

### Mobile
- **Cartes empilées** : Une église par ligne
- **Boutons adaptés** : Taille optimisée pour le tactile
- **Navigation simplifiée** : Menu hamburger

### Desktop
- **Grille 2 colonnes** : Affichage optimisé
- **Actions multiples** : Boutons groupés
- **Filtres avancés** : Recherche et tri

## 🚀 Utilisation

### Accès initial
1. Se connecter avec un compte super admin
2. Cliquer sur "Administration" dans la sidebar
3. Accéder au tableau de bord global

### Créer un abonnement
1. Cliquer sur "Détails" d'une église
2. Cliquer sur "Nouvel abonnement"
3. Sélectionner le plan et configurer
4. Valider la création

### Gérer les paiements
1. Identifier les abonnements en attente
2. Cliquer sur "Marquer payé"
3. Confirmer l'action

### Exporter les données
1. Cliquer sur "Exporter" dans l'AppBar
2. Télécharger le fichier CSV
3. Ouvrir dans Excel ou Google Sheets

## 🔧 Maintenance

### Ajout d'un super admin
```php
$user = User::find($id);
$user->is_super_admin = true;
$user->save();
```

### Vérification des permissions
```php
if (Auth::user()->is_super_admin) {
    // Accès autorisé
}
```

## 📈 Évolutions futures

### Fonctionnalités prévues
- **Tableau de bord avancé** : Graphiques et KPIs
- **Notifications** : Alertes d'expiration
- **Facturation automatique** : Génération de factures
- **API d'administration** : Intégration externe
- **Audit trail** : Historique des actions

### Améliorations UX
- **Recherche avancée** : Filtres multiples
- **Actions en lot** : Sélection multiple
- **Templates d'abonnement** : Plans prédéfinis
- **Rapports personnalisés** : Export configurable

---

## 🎯 Résumé

Le système d'administration Eglix offre une solution complète pour :
- ✅ Gérer toutes les églises de la plateforme
- ✅ Contrôler les abonnements et paiements
- ✅ Exporter les données pour analyse
- ✅ Interface moderne et responsive
- ✅ Sécurité et contrôle d'accès

**Route d'accès** : `/admin-0202` (super admin uniquement)
