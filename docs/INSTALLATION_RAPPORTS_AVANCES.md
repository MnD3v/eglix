# 📦 INSTALLATION DES DÉPENDANCES POUR RAPPORTS AVANCÉS

## 🎯 **Dépendances Requises**

Pour utiliser le système de rapports avancés, vous devez installer les packages suivants :

### **1. PhpSpreadsheet (Excel)**
```bash
composer require phpoffice/phpspreadsheet
```

### **2. DomPDF (PDF)**
```bash
composer require barryvdh/laravel-dompdf
```

### **3. Configuration DomPDF**
Ajoutez dans `config/app.php` :
```php
'providers' => [
    // ...
    Barryvdh\DomPDF\ServiceProvider::class,
],

'aliases' => [
    // ...
    'PDF' => Barryvdh\DomPDF\Facade::class,
],
```

## 🚀 **Installation Complète**

```bash
# 1. Installer les dépendances
composer require phpoffice/phpspreadsheet
composer require barryvdh/laravel-dompdf

# 2. Publier la configuration DomPDF
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

# 3. Vérifier l'installation
php artisan route:list | grep reports.advanced
```

## ✅ **Vérification**

Après installation, vous devriez pouvoir accéder à :
- `/reports/advanced` - Dashboard des rapports avancés
- `/reports/advanced/export/excel` - Export Excel
- `/reports/advanced/export/pdf` - Export PDF
- `/reports/advanced/export/json` - Export JSON
- `/reports/advanced/export/csv` - Export CSV

## 🔧 **Configuration Optionnelle**

### **Limites de Mémoire (PHP)**
Pour les gros exports, augmentez la limite de mémoire :
```php
// Dans le contrôleur
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);
```

### **Cache des Rapports**
Pour optimiser les performances, vous pouvez ajouter un cache :
```php
// Dans AdvancedReportService
$cacheKey = "report_{$this->churchId}_{$this->from}_{$this->to}";
return Cache::remember($cacheKey, 3600, function() {
    return $this->generateComprehensiveReport();
});
```

