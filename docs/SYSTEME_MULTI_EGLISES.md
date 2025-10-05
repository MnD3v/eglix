# 🏛️ Système Multi-Églises - Eglix

## 📋 Vue d'ensemble

Le système multi-églises permet à un pasteur ou administrateur d'être responsable de plusieurs églises et de basculer facilement entre elles pour consulter et gérer les données de chaque église.

## 🔧 Fonctionnalités Implémentées

### ✅ **1. Base de Données**
- **Table pivot `user_churches`** : Gère les relations utilisateur-église
- **Migration automatique** : Migre les données existantes de `church_id` vers le nouveau système
- **Champs pivot** :
  - `is_primary` : Église principale de l'utilisateur
  - `is_active` : Si l'utilisateur a accès à cette église

### ✅ **2. Modèle User Étendu**
- **Relations multiples** : `churches()`, `primaryChurch()`, `activeChurches()`
- **Méthodes utilitaires** :
  - `getCurrentChurch()` : Récupère l'église active (depuis la session)
  - `setCurrentChurch($churchId)` : Change l'église active
  - `hasAccessToChurch($churchId)` : Vérifie l'accès à une église

### ✅ **3. Interface Utilisateur**
- **Sélecteur d'église** dans la sidebar (visible si plusieurs églises)
- **Page de sélection** pour les utilisateurs sans église active
- **Changement en temps réel** avec AJAX
- **Indicateurs visuels** (église principale, chargement)

### ✅ **4. Sécurité et Middleware**
- **Middleware `EnsureActiveChurch`** : Vérifie qu'un utilisateur a une église active
- **Vérification d'accès** : Empêche l'accès aux églises non autorisées
- **Session sécurisée** : Stockage de l'église active en session

### ✅ **5. Helpers et Utilitaires**
- **`get_current_church_id()`** : Récupère l'ID de l'église courante
- **`get_current_church()`** : Récupère l'objet église courante
- **Intégration facile** dans les contrôleurs existants

## 🚀 Installation et Migration

### **1. Exécuter les Migrations**
```bash
php artisan migrate
```

### **2. Migrer les Utilisateurs Existants**
```bash
php artisan users:migrate-to-multi-church
```

### **3. Ajouter un Utilisateur à une Église**
```bash
# Ajouter un utilisateur à une église
php artisan user:add-to-church {user_id} {church_id}

# Ajouter comme église principale
php artisan user:add-to-church {user_id} {church_id} --primary
```

## 📖 Utilisation

### **Pour les Administrateurs**

#### **Ajouter un Pasteur à Plusieurs Églises**
```bash
# Ajouter le pasteur (ID: 1) à l'église principale (ID: 4)
php artisan user:add-to-church 1 4 --primary

# Ajouter le même pasteur à une autre église (ID: 5)
php artisan user:add-to-church 1 5
```

#### **Via l'Interface Web**
1. Connectez-vous en tant qu'administrateur
2. Allez dans "Gestion des Utilisateurs"
3. Modifiez l'utilisateur et assignez plusieurs églises

### **Pour les Utilisateurs Multi-Églises**

#### **Changer d'Église**
1. **Via la Sidebar** : Sélectionnez l'église dans le menu déroulant
2. **Page de Sélection** : Si aucune église active, redirection automatique
3. **Changement Instantané** : Les données se mettent à jour automatiquement

#### **Navigation**
- **Église Active** : Affichée en haut de la sidebar
- **Sélecteur** : Visible uniquement si plusieurs églises accessibles
- **Église Principale** : Marquée avec "(Principal)" dans le sélecteur

## 🔧 Intégration dans le Code

### **Dans les Contrôleurs**
```php
// Ancien code
$churchId = Auth::user()->church_id;

// Nouveau code
$churchId = get_current_church_id();
// ou
$church = get_current_church();
$churchId = $church->id;
```

### **Dans les Vues**
```php
// Ancien code
{{ Auth::user()->church->name }}

// Nouveau code
{{ Auth::user()->getCurrentChurch()->name }}
```

### **Dans les Middlewares**
```php
// Ajouter le middleware aux routes
Route::middleware(['auth', 'ensure.active.church'])->group(function () {
    // Routes protégées
});
```

## 🛡️ Sécurité

### **Vérifications Automatiques**
- ✅ **Accès aux églises** : Vérification avant chaque changement
- ✅ **Session sécurisée** : Église active stockée en session
- ✅ **Middleware de protection** : Empêche l'accès sans église active
- ✅ **Validation des données** : Vérification côté serveur

### **Isolation des Données**
- ✅ **Séparation par église** : Chaque église voit uniquement ses données
- ✅ **Permissions maintenues** : Les rôles et permissions restent inchangés
- ✅ **Audit trail** : Traçabilité des changements d'église

## 📊 Exemples d'Usage

### **Scénario 1 : Pasteur de Plusieurs Églises**
```bash
# Pasteur Jean (ID: 1) responsable de 3 églises
php artisan user:add-to-church 1 4 --primary  # Église A (principale)
php artisan user:add-to-church 1 5             # Église B
php artisan user:add-to-church 1 6             # Église C
```

### **Scénario 2 : Administrateur Régional**
```bash
# Admin Marie (ID: 2) supervise toutes les églises
php artisan user:add-to-church 2 4 --primary
php artisan user:add-to-church 2 5
php artisan user:add-to-church 2 6
php artisan user:add-to-church 2 7
```

## 🔄 Migration des Données Existantes

### **Processus Automatique**
1. **Sauvegarde** : Les données existantes sont préservées
2. **Migration** : `church_id` → `user_churches` (avec `is_primary = true`)
3. **Suppression** : Colonne `church_id` supprimée après migration
4. **Rollback** : Possibilité de revenir en arrière si nécessaire

### **Vérification Post-Migration**
```bash
# Vérifier les associations créées
php artisan tinker
>>> DB::table('user_churches')->get();

# Vérifier qu'un utilisateur a accès à ses églises
>>> User::find(1)->activeChurches()->get();
```

## 🐛 Dépannage

### **Problème : "Aucune église active"**
- **Cause** : Utilisateur sans église assignée
- **Solution** : Utiliser `php artisan user:add-to-church {user_id} {church_id}`

### **Problème : Sélecteur non visible**
- **Cause** : Utilisateur avec une seule église
- **Solution** : Ajouter l'utilisateur à une autre église

### **Problème : Données incorrectes après changement**
- **Cause** : Cache ou session non mise à jour
- **Solution** : Recharger la page ou vider le cache

## 📈 Avantages du Nouveau Système

### **✅ Flexibilité**
- Un pasteur peut gérer plusieurs églises
- Changement d'église en un clic
- Interface unifiée pour toutes les églises

### **✅ Sécurité Renforcée**
- Vérification d'accès à chaque changement
- Isolation des données par église
- Audit trail complet

### **✅ Facilité d'Utilisation**
- Interface intuitive
- Changement en temps réel
- Indicateurs visuels clairs

### **✅ Évolutivité**
- Support de nombreux utilisateurs multi-églises
- Architecture extensible
- Performance optimisée

## 🎯 Prochaines Améliorations

- [ ] **Interface d'administration** pour gérer les associations utilisateur-église
- [ ] **Notifications** lors des changements d'église
- [ ] **Rapports multi-églises** pour les administrateurs régionaux
- [ ] **API endpoints** pour intégrations externes
- [ ] **Dashboard unifié** avec vue d'ensemble de toutes les églises

---

Le système multi-églises d'Eglix offre une solution complète et sécurisée pour la gestion de plusieurs églises par un même utilisateur, tout en maintenant l'isolation des données et la facilité d'utilisation.
