# 👤 Système Multi-Églises Utilisateur - Eglix

## 📋 Vue d'ensemble

Le système multi-églises utilisateur permet à chaque utilisateur de gérer ses propres églises. Chaque utilisateur peut :
- Voir uniquement SES églises (celles qu'il dirige)
- Ajouter/retirer des églises de sa liste
- Définir une église principale
- Basculer entre ses églises

## 🎯 Fonctionnalités Implémentées

### ✅ **1. Page de Gestion des Églises Utilisateur**
- **Route** : `/user/churches`
- **Accès** : Visible dans la sidebar si l'utilisateur a plusieurs églises ou est admin
- **Fonctionnalités** :
  - Vue en cartes de toutes les églises de l'utilisateur
  - Indication de l'église principale
  - Indication de l'église actuellement active
  - Actions : basculer, définir comme principale, retirer l'accès

### ✅ **2. Interface d'Ajout d'Églises**
- **Modal** : Ajout d'églises disponibles
- **Sélection** : Liste des églises auxquelles l'utilisateur n'a pas encore accès
- **Options** : Possibilité de définir comme église principale

### ✅ **3. Sélecteur d'Église Amélioré**
- **Sidebar** : Sélecteur visible uniquement si plusieurs églises
- **Indicateurs** : Église principale marquée "(Principal)"
- **Actions** : Changement d'église en temps réel
- **Lien** : Lien vers la gestion des églises si une seule église

### ✅ **4. Contrôleur UserChurchesController**
- **Méthodes** :
  - `index()` : Affiche la page de gestion
  - `addChurch()` : Ajoute une église à l'utilisateur
  - `setPrimary()` : Définit une église comme principale
  - `removeChurch()` : Retire l'accès à une église
  - `getUserChurches()` : API pour récupérer les églises

### ✅ **5. Commandes Artisan**
- **`user:manage-churches`** : Gestion complète des églises utilisateur
  - `list` : Liste les églises d'un utilisateur
  - `add` : Ajoute un utilisateur à une église
  - `remove` : Retire un utilisateur d'une église
  - `set-primary` : Définit une église comme principale

## 🚀 Utilisation

### **Pour l'Utilisateur**

#### **Accéder à la Gestion des Églises**
1. **Via la Sidebar** : Cliquer sur "Mes Églises"
2. **Via le Sélecteur** : Lien "Gérer mes églises" si une seule église

#### **Ajouter une Église**
1. Cliquer sur "Ajouter une Église"
2. Sélectionner une église dans la liste
3. Optionnellement cocher "Définir comme église principale"
4. Cliquer sur "Ajouter"

#### **Changer d'Église**
1. **Via la Sidebar** : Sélectionner dans le menu déroulant
2. **Via la Page** : Cliquer sur "Basculer vers cette Église"

#### **Définir une Église Principale**
1. Aller dans "Mes Églises"
2. Cliquer sur le menu ⋮ de l'église souhaitée
3. Sélectionner "Définir comme Principale"

#### **Retirer l'Accès à une Église**
1. Aller dans "Mes Églises"
2. Cliquer sur le menu ⋮ de l'église
3. Sélectionner "Retirer l'Accès"
4. Confirmer l'action

### **Pour l'Administrateur**

#### **Via les Commandes Artisan**
```bash
# Lister les églises d'un utilisateur
php artisan user:manage-churches list {user_id}

# Ajouter un utilisateur à une église
php artisan user:manage-churches add {user_id} {church_id}

# Ajouter comme église principale
php artisan user:manage-churches add {user_id} {church_id} --primary

# Retirer un utilisateur d'une église
php artisan user:manage-churches remove {user_id} {church_id}

# Définir une église comme principale
php artisan user:manage-churches set-primary {user_id} {church_id}
```

#### **Exemples Pratiques**
```bash
# Pasteur Jean (ID: 1) responsable de 3 églises
php artisan user:manage-churches add 1 4 --primary  # Église A (principale)
php artisan user:manage-churches add 1 5             # Église B
php artisan user:manage-churches add 1 6             # Église C

# Voir les églises de Jean
php artisan user:manage-churches list 1

# Changer l'église principale de Jean
php artisan user:manage-churches set-primary 1 5
```

## 🔧 Intégration dans le Code

### **Dans les Contrôleurs**
```php
// Récupérer l'église courante de l'utilisateur
$churchId = get_current_church_id();
$church = get_current_church();

// Vérifier l'accès à une église
if (Auth::user()->hasAccessToChurch($churchId)) {
    // L'utilisateur a accès à cette église
}
```

### **Dans les Vues**
```php
// Afficher le nom de l'église courante
{{ Auth::user()->getCurrentChurch()->name }}

// Vérifier si l'utilisateur a plusieurs églises
@if(Auth::user()->activeChurches()->count() > 1)
    <!-- Afficher le sélecteur -->
@endif
```

### **Dans les Routes**
```php
// Routes protégées par église active
Route::middleware(['auth', 'ensure.active.church'])->group(function () {
    // Routes qui nécessitent une église active
});
```

## 🛡️ Sécurité

### **Vérifications Automatiques**
- ✅ **Accès aux églises** : Vérification avant chaque action
- ✅ **Église principale** : Une seule église principale par utilisateur
- ✅ **Dernière église** : Impossible de retirer la dernière église
- ✅ **Session sécurisée** : Église active stockée en session

### **Isolation des Données**
- ✅ **Séparation par utilisateur** : Chaque utilisateur voit uniquement ses églises
- ✅ **Séparation par église** : Chaque église voit uniquement ses données
- ✅ **Permissions maintenues** : Les rôles et permissions restent inchangés

## 📊 Interface Utilisateur

### **Page de Gestion des Églises**
- **Design** : Cartes modernes avec informations complètes
- **Actions** : Menu déroulant avec toutes les options
- **Indicateurs** : Église principale, église active, date d'ajout
- **Responsive** : Adapté mobile et desktop

### **Sélecteur dans la Sidebar**
- **Visibilité** : Visible uniquement si plusieurs églises
- **Fonctionnalité** : Changement en temps réel
- **Indicateurs** : Église principale marquée
- **Lien** : Accès direct à la gestion si une seule église

### **Modal d'Ajout**
- **Sélection** : Liste des églises disponibles
- **Options** : Définir comme principale
- **Validation** : Vérification côté client et serveur

## 🐛 Dépannage

### **Problème : "Mes Églises" non visible**
- **Cause** : Utilisateur avec une seule église
- **Solution** : Ajouter l'utilisateur à une autre église

### **Problème : Impossible d'ajouter une église**
- **Cause** : Église déjà assignée ou inexistante
- **Solution** : Vérifier la liste des églises disponibles

### **Problème : Changement d'église ne fonctionne pas**
- **Cause** : Session ou cache non mis à jour
- **Solution** : Recharger la page ou vider le cache

### **Problème : Impossible de retirer la dernière église**
- **Cause** : Protection contre la suppression de la dernière église
- **Solution** : Ajouter une autre église avant de retirer celle-ci

## 📈 Avantages du Système

### **✅ Personnalisation**
- Chaque utilisateur gère ses propres églises
- Interface adaptée au nombre d'églises
- Actions contextuelles selon la situation

### **✅ Flexibilité**
- Ajout/retrait d'églises en temps réel
- Changement d'église principale
- Gestion granulaire des accès

### **✅ Sécurité**
- Vérification d'accès à chaque action
- Protection contre les suppressions dangereuses
- Isolation des données par utilisateur

### **✅ Facilité d'Utilisation**
- Interface intuitive avec cartes
- Actions rapides via menus déroulants
- Indicateurs visuels clairs

## 🎯 Prochaines Améliorations

- [ ] **Notifications** lors des changements d'église
- [ ] **Historique** des changements d'église
- [ ] **Permissions granulaires** par église
- [ ] **API endpoints** pour intégrations externes
- [ ] **Export** de la liste des églises utilisateur

---

Le système multi-églises utilisateur d'Eglix offre une solution complète et personnalisée pour la gestion des églises par utilisateur, permettant à chaque pasteur ou administrateur de gérer efficacement ses églises assignées.
