# Système de Gestion des Abonnements - Eglix

## Vue d'ensemble

Le système d'abonnements d'Eglix permet de gérer les abonnements annuels, mensuels et trimestriels des membres de l'église avec un suivi complet des paiements physiques.

## Fonctionnalités principales

### 1. Types d'abonnements
- **Annuel** : Abonnement d'une durée de 12 mois
- **Mensuel** : Abonnement d'une durée de 1 mois
- **Trimestriel** : Abonnement d'une durée de 3 mois

### 2. Gestion des paiements
- **Méthodes de paiement** :
  - Espèces
  - Virement bancaire
  - Mobile Money
  - Chèque

- **Statuts de paiement** :
  - En attente
  - Payé
  - En retard
  - Annulé

### 3. Statuts d'abonnement
- **Actif** : Abonnement en cours et valide
- **Expiré** : Abonnement arrivé à échéance
- **Suspendu** : Abonnement temporairement suspendu
- **Annulé** : Abonnement définitivement annulé

## Architecture technique

### Modèle Subscription
```php
// Relations
- church(): BelongsTo (Église)
- member(): BelongsTo (Membre)
- createdBy(): BelongsTo (Utilisateur créateur)
- updatedBy(): BelongsTo (Utilisateur modificateur)

// Scopes disponibles
- active(), expired(), paid(), pending(), overdue()
- annual(), monthly(), quarterly()

// Méthodes utilitaires
- renew(int $months): Renouveler l'abonnement
- markAsPaid(): Marquer comme payé
- suspend(): Suspendre l'abonnement
- cancel(): Annuler l'abonnement
```

### Contrôleur SubscriptionController
- **CRUD complet** : index, create, store, show, edit, update, destroy
- **Actions spécialisées** :
  - `markAsPaid()` : Marquer un abonnement comme payé
  - `renew()` : Renouveler un abonnement
  - `suspend()` : Suspendre un abonnement
  - `cancel()` : Annuler un abonnement

### Base de données
```sql
-- Table subscriptions
- id (Primary Key)
- church_id (Foreign Key vers churches)
- member_id (Foreign Key vers members)
- subscription_type (annual, monthly, quarterly)
- amount (Montant de l'abonnement)
- currency (Devise : XOF, EUR, USD)
- start_date (Date de début)
- end_date (Date de fin)
- payment_date (Date de paiement)
- renewal_date (Date de renouvellement prévue)
- status (active, expired, suspended, cancelled)
- payment_status (pending, paid, overdue, cancelled)
- payment_method (cash, bank_transfer, mobile_money, check)
- notes (Notes de l'admin)
- receipt_number (Numéro de reçu)
- payment_reference (Référence de paiement)
- created_by (Foreign Key vers users)
- updated_by (Foreign Key vers users)
- timestamps
```

## Interface utilisateur

### Page d'accueil des abonnements
- **Statistiques** : Total, actifs, expirés, payés, en attente, revenus
- **Filtres** : Recherche, statut, paiement, type
- **Cartes d'abonnement** : Affichage moderne avec informations clés
- **Actions rapides** : Voir, modifier, marquer payé, supprimer

### Création d'abonnement
- **Sélection du membre** : Liste filtrée par église
- **Configuration automatique** : Calcul des dates selon le type
- **Informations de paiement** : Méthode et statut
- **Validation** : Contrôles de cohérence des dates

### Détail d'abonnement
- **Informations complètes** : Membre, montant, dates, statuts
- **Actions contextuelles** : Renouveler, suspendre, annuler
- **Historique** : Traçabilité des modifications
- **Modals d'action** : Interface intuitive pour les opérations

## Sécurité et permissions

### Filtrage par église
- Tous les abonnements sont automatiquement filtrés par `church_id`
- Les utilisateurs ne peuvent voir que les abonnements de leur église
- Protection contre l'accès non autorisé aux données

### Permissions requises
- `subscriptions.view` : Accès en lecture
- `subscriptions.create` : Création d'abonnements
- `subscriptions.edit` : Modification d'abonnements
- `subscriptions.delete` : Suppression d'abonnements

## Workflow typique

### 1. Création d'un abonnement
1. Sélectionner le membre
2. Choisir le type d'abonnement
3. Définir le montant et la devise
4. Configurer les dates (calcul automatique)
5. Choisir la méthode de paiement
6. Définir le statut initial

### 2. Gestion des paiements
1. **Paiement en attente** : L'abonnement est créé avec statut "pending"
2. **Paiement reçu** : Utiliser "Marquer comme payé" avec les détails
3. **Suivi** : Numéro de reçu et référence de paiement

### 3. Renouvellement
1. Accéder au détail de l'abonnement
2. Utiliser "Renouveler" avec la durée souhaitée
3. Le système étend automatiquement la date de fin
4. Le statut de paiement repasse à "pending"

### 4. Gestion des problèmes
- **Suspension** : Temporaire avec raison
- **Annulation** : Définitive avec raison
- **Notes** : Suivi des actions administratives

## Intégration avec l'écosystème Eglix

### Navigation
- Lien ajouté dans la sidebar principale
- Icône : `bi-credit-card`
- Position : Entre "Dons" et "Dépenses"

### AppBar cohérent
- Design uniforme avec les autres sections
- Couleur : Bleu (#1a73e8)
- Icône : Carte de crédit
- Actions contextuelles

### Responsive design
- Cartes adaptatives sur mobile
- Modals optimisées pour petits écrans
- Navigation tactile intuitive

## Avantages du système

### Pour les administrateurs
- **Suivi complet** : Tous les abonnements centralisés
- **Gestion flexible** : Statuts et actions multiples
- **Traçabilité** : Historique des modifications
- **Rapports** : Statistiques en temps réel

### Pour l'église
- **Revenus prévisibles** : Abonnements récurrents
- **Suivi des membres** : Engagement financier
- **Transparence** : Traçabilité des paiements
- **Efficacité** : Automatisation des processus

## Évolutions futures possibles

### Fonctionnalités avancées
- **Rappels automatiques** : Notifications avant expiration
- **Paiements en ligne** : Intégration avec des passerelles
- **Rapports détaillés** : Analytics et projections
- **API** : Intégration avec systèmes externes

### Améliorations UX
- **Calendrier** : Vue calendaire des échéances
- **Notifications** : Alertes en temps réel
- **Export** : PDF et Excel des abonnements
- **Import** : Import en masse depuis Excel

---

*Système développé avec Laravel 12, Bootstrap 5, et une approche MIT pour la simplicité et l'efficacité.*
