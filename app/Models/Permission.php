<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'action'
    ];

    /**
     * Get all permissions grouped by module
     */
    public static function getGroupedPermissions(): array
    {
        $permissions = self::all();
        $grouped = [];

        foreach ($permissions as $permission) {
            $grouped[$permission->module][] = $permission;
        }

        return $grouped;
    }

    /**
     * Seed default permissions
     */
    public static function seedDefaultPermissions(): void
    {
        $defaultPermissions = [
            // Membres
            ['name' => 'Voir les membres', 'slug' => 'members.view', 'module' => 'members', 'action' => 'view'],
            ['name' => 'Créer des membres', 'slug' => 'members.create', 'module' => 'members', 'action' => 'create'],
            ['name' => 'Modifier les membres', 'slug' => 'members.edit', 'module' => 'members', 'action' => 'edit'],
            ['name' => 'Supprimer les membres', 'slug' => 'members.delete', 'module' => 'members', 'action' => 'delete'],
            
            // Dîmes
            ['name' => 'Voir les dîmes', 'slug' => 'tithes.view', 'module' => 'tithes', 'action' => 'view'],
            ['name' => 'Créer des dîmes', 'slug' => 'tithes.create', 'module' => 'tithes', 'action' => 'create'],
            ['name' => 'Modifier les dîmes', 'slug' => 'tithes.edit', 'module' => 'tithes', 'action' => 'edit'],
            ['name' => 'Supprimer les dîmes', 'slug' => 'tithes.delete', 'module' => 'tithes', 'action' => 'delete'],
            
            // Offrandes
            ['name' => 'Voir les offrandes', 'slug' => 'offerings.view', 'module' => 'offerings', 'action' => 'view'],
            ['name' => 'Créer des offrandes', 'slug' => 'offerings.create', 'module' => 'offerings', 'action' => 'create'],
            ['name' => 'Modifier les offrandes', 'slug' => 'offerings.edit', 'module' => 'offerings', 'action' => 'edit'],
            ['name' => 'Supprimer les offrandes', 'slug' => 'offerings.delete', 'module' => 'offerings', 'action' => 'delete'],
            ['name' => 'Gérer les types d\'offrandes', 'slug' => 'offerings.types', 'module' => 'offerings', 'action' => 'manage'],
            
            // Dons
            ['name' => 'Voir les dons', 'slug' => 'donations.view', 'module' => 'donations', 'action' => 'view'],
            ['name' => 'Créer des dons', 'slug' => 'donations.create', 'module' => 'donations', 'action' => 'create'],
            ['name' => 'Modifier les dons', 'slug' => 'donations.edit', 'module' => 'donations', 'action' => 'edit'],
            ['name' => 'Supprimer les dons', 'slug' => 'donations.delete', 'module' => 'donations', 'action' => 'delete'],
            
            // Dépenses
            ['name' => 'Voir les dépenses', 'slug' => 'expenses.view', 'module' => 'expenses', 'action' => 'view'],
            ['name' => 'Créer des dépenses', 'slug' => 'expenses.create', 'module' => 'expenses', 'action' => 'create'],
            ['name' => 'Modifier les dépenses', 'slug' => 'expenses.edit', 'module' => 'expenses', 'action' => 'edit'],
            ['name' => 'Supprimer les dépenses', 'slug' => 'expenses.delete', 'module' => 'expenses', 'action' => 'delete'],
            
            // Projets
            ['name' => 'Voir les projets', 'slug' => 'projects.view', 'module' => 'projects', 'action' => 'view'],
            ['name' => 'Créer des projets', 'slug' => 'projects.create', 'module' => 'projects', 'action' => 'create'],
            ['name' => 'Modifier les projets', 'slug' => 'projects.edit', 'module' => 'projects', 'action' => 'edit'],
            ['name' => 'Supprimer les projets', 'slug' => 'projects.delete', 'module' => 'projects', 'action' => 'delete'],
            
            // Cultes
            ['name' => 'Voir les cultes', 'slug' => 'services.view', 'module' => 'services', 'action' => 'view'],
            ['name' => 'Créer des cultes', 'slug' => 'services.create', 'module' => 'services', 'action' => 'create'],
            ['name' => 'Modifier les cultes', 'slug' => 'services.edit', 'module' => 'services', 'action' => 'edit'],
            ['name' => 'Supprimer les cultes', 'slug' => 'services.delete', 'module' => 'services', 'action' => 'delete'],
            ['name' => 'Gérer les rôles de culte', 'slug' => 'services.roles', 'module' => 'services', 'action' => 'manage'],
            
            // Événements
            ['name' => 'Voir les événements', 'slug' => 'events.view', 'module' => 'events', 'action' => 'view'],
            ['name' => 'Créer des événements', 'slug' => 'events.create', 'module' => 'events', 'action' => 'create'],
            ['name' => 'Modifier les événements', 'slug' => 'events.edit', 'module' => 'events', 'action' => 'edit'],
            ['name' => 'Supprimer les événements', 'slug' => 'events.delete', 'module' => 'events', 'action' => 'delete'],
            
            // Rapports
            ['name' => 'Voir les rapports', 'slug' => 'reports.view', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'Exporter les rapports', 'slug' => 'reports.export', 'module' => 'reports', 'action' => 'export'],
            
            // Administration
            ['name' => 'Voir l\'administration', 'slug' => 'administration.view', 'module' => 'administration', 'action' => 'view'],
            ['name' => 'Créer des fonctions d\'administration', 'slug' => 'administration.create', 'module' => 'administration', 'action' => 'create'],
            ['name' => 'Modifier l\'administration', 'slug' => 'administration.edit', 'module' => 'administration', 'action' => 'edit'],
            ['name' => 'Supprimer l\'administration', 'slug' => 'administration.delete', 'module' => 'administration', 'action' => 'delete'],
            
            // Journal
            ['name' => 'Voir le journal', 'slug' => 'journal.view', 'module' => 'journal', 'action' => 'view'],
            ['name' => 'Créer des entrées de journal', 'slug' => 'journal.create', 'module' => 'journal', 'action' => 'create'],
            ['name' => 'Modifier le journal', 'slug' => 'journal.edit', 'module' => 'journal', 'action' => 'edit'],
            ['name' => 'Supprimer le journal', 'slug' => 'journal.delete', 'module' => 'journal', 'action' => 'delete'],
            
            // Utilisateurs
            ['name' => 'Voir les utilisateurs', 'slug' => 'users.view', 'module' => 'users', 'action' => 'view'],
            ['name' => 'Créer des utilisateurs', 'slug' => 'users.create', 'module' => 'users', 'action' => 'create'],
            ['name' => 'Modifier les utilisateurs', 'slug' => 'users.edit', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'Supprimer les utilisateurs', 'slug' => 'users.delete', 'module' => 'users', 'action' => 'delete'],
            ['name' => 'Gérer les rôles', 'slug' => 'users.roles', 'module' => 'users', 'action' => 'manage'],
            
            // Paramètres
            ['name' => 'Voir les paramètres', 'slug' => 'settings.view', 'module' => 'settings', 'action' => 'view'],
            ['name' => 'Modifier les paramètres', 'slug' => 'settings.edit', 'module' => 'settings', 'action' => 'edit'],
        ];

        foreach ($defaultPermissions as $permission) {
            self::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}