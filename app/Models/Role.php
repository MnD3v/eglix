<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'name',
        'slug',
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $baseSlug = Str::slug($role->name);
                
                // Ajouter un timestamp et un hash aléatoire pour garantir l'unicité
                $timestamp = time();
                $random = substr(md5(uniqid(mt_rand(), true)), 0, 6);
                
                // Si l'ID de l'église est disponible, l'inclure dans le slug
                if (!empty($role->church_id)) {
                    $slug = "{$baseSlug}-{$role->church_id}-{$timestamp}-{$random}";
                } else {
                    $slug = "{$baseSlug}-{$timestamp}-{$random}";
                }
                
                $role->slug = $slug;
            }
        });
    }

    /**
     * Get the church that owns the role
     */
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    /**
     * Get the users for the role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        // Accept exact permission
        if (in_array($permission, $permissions, true)) {
            return true;
        }
        // Accept module-level shorthand (e.g. "members" grants view access in menus)
        $module = explode('.', $permission)[0] ?? $permission;
        if (in_array($module, $permissions, true)) {
            return true;
        }
        // Accept wildcard style (e.g. "members.*")
        $wildcard = $module . '.*';
        if (in_array($wildcard, $permissions, true)) {
            return true;
        }
        return false;
    }

    /**
     * Add permission to role
     */
    public function addPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Remove permission from role
     */
    public function removePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions(): array
    {
        return [
            // Membres
            'members.view' => 'Voir les membres',
            'members.create' => 'Créer des membres',
            'members.edit' => 'Modifier les membres',
            'members.delete' => 'Supprimer les membres',
            
            // Dîmes
            'tithes.view' => 'Voir les dîmes',
            'tithes.create' => 'Créer des dîmes',
            'tithes.edit' => 'Modifier les dîmes',
            'tithes.delete' => 'Supprimer les dîmes',
            
            // Offrandes
            'offerings.view' => 'Voir les offrandes',
            'offerings.create' => 'Créer des offrandes',
            'offerings.edit' => 'Modifier les offrandes',
            'offerings.delete' => 'Supprimer les offrandes',
            'offerings.types' => 'Gérer les types d\'offrandes',
            
            // Dons
            'donations.view' => 'Voir les dons',
            'donations.create' => 'Créer des dons',
            'donations.edit' => 'Modifier les dons',
            'donations.delete' => 'Supprimer les dons',
            
            // Dépenses
            'expenses.view' => 'Voir les dépenses',
            'expenses.create' => 'Créer des dépenses',
            'expenses.edit' => 'Modifier les dépenses',
            'expenses.delete' => 'Supprimer les dépenses',
            
            // Projets
            'projects.view' => 'Voir les projets',
            'projects.create' => 'Créer des projets',
            'projects.edit' => 'Modifier les projets',
            'projects.delete' => 'Supprimer les projets',
            
            
            // Rapports
            'reports.view' => 'Voir les rapports',
            'reports.export' => 'Exporter les rapports',
            
            // Administration
            'administration.view' => 'Voir l\'administration',
            'administration.create' => 'Créer des fonctions d\'administration',
            'administration.edit' => 'Modifier l\'administration',
            'administration.delete' => 'Supprimer l\'administration',
            
            // Journal
            'journal.view' => 'Voir le journal',
            'journal.create' => 'Créer des entrées de journal',
            'journal.edit' => 'Modifier le journal',
            'journal.delete' => 'Supprimer le journal',
            
            // Documents
            'documents.view' => 'Voir les documents',
            'documents.create' => 'Créer des documents',
            'documents.edit' => 'Modifier les documents',
            'documents.delete' => 'Supprimer les documents',
            'documents.download' => 'Télécharger les documents',
            'documents.folders' => 'Gérer les dossiers de documents',
            
            // Utilisateurs
            'users.view' => 'Voir les utilisateurs',
            'users.create' => 'Créer des utilisateurs',
            'users.edit' => 'Modifier les utilisateurs',
            'users.delete' => 'Supprimer les utilisateurs',
            'users.roles' => 'Gérer les rôles',
            
            // Paramètres
            'settings.view' => 'Voir les paramètres',
            'settings.edit' => 'Modifier les paramètres',
        ];
    }
}