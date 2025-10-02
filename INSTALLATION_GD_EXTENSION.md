# Installation de l'extension PHP GD pour l'export PDF

## Problème
L'export PDF des dossiers membres nécessite l'extension PHP GD pour traiter les images (logo Eglix).

**Erreur rencontrée :**
```
The PHP GD extension is required, but is not installed.
```

## Solutions d'installation

### 1. Windows (XAMPP/WAMP)
```bash
# Éditer le fichier php.ini
# Décommenter la ligne :
extension=gd

# Redémarrer Apache
```

### 2. Windows (PHP standalone)
```bash
# Dans php.ini, ajouter ou décommenter :
extension=gd
extension=gd2

# Redémarrer le serveur web
```

### 3. Ubuntu/Debian
```bash
sudo apt-get update
sudo apt-get install php-gd
sudo systemctl restart apache2
# ou
sudo systemctl restart nginx
```

### 4. CentOS/RHEL
```bash
sudo yum install php-gd
# ou pour les versions récentes
sudo dnf install php-gd
sudo systemctl restart httpd
```

### 5. macOS (Homebrew)
```bash
brew install php
# GD est généralement inclus dans l'installation PHP de Homebrew
```

### 6. Docker
```dockerfile
# Dans votre Dockerfile
RUN docker-php-ext-install gd
```

## Vérification de l'installation

```bash
# Vérifier que GD est installé
php -m | grep -i gd

# Ou créer un fichier PHP avec :
<?php
if (extension_loaded('gd')) {
    echo "GD extension is loaded";
} else {
    echo "GD extension is NOT loaded";
}
?>
```

## Solution temporaire (sans images)

En attendant l'installation de GD, le PDF utilise le nom "EGLIX" en texte stylisé au lieu du logo image.

## Restauration du logo après installation

Une fois GD installé, vous pouvez restaurer le logo en modifiant `resources/views/members/pdf.blade.php` :

```html
<!-- Remplacer le texte EGLIX par : -->
<img src="{{ public_path('images/eglix-black.png') }}" alt="Eglix" class="logo">
```

## Test après installation

1. Redémarrer le serveur web
2. Tester l'export PDF d'un membre
3. Vérifier que le logo s'affiche correctement
