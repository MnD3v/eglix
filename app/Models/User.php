<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'church_name',
        'email',
        'password',
        'role_id',
        'is_church_admin',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_church_admin' => 'boolean',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get the churches that the user belongs to
     */
    public function churches(): BelongsToMany
    {
        return $this->belongsToMany(Church::class, 'user_churches')
            ->withPivot(['is_primary', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Get the primary church of the user
     */
    public function primaryChurch(): BelongsToMany
    {
        return $this->churches()->wherePivot('is_primary', true);
    }

    /**
     * Get the active churches of the user
     */
    public function activeChurches(): BelongsToMany
    {
        return $this->churches()->wherePivot('is_active', true);
    }

    /**
     * Get the current active church (from session)
     */
    public function getCurrentChurch()
    {
        $currentChurchId = session('current_church_id');
        if ($currentChurchId) {
            return $this->churches()->where('churches.id', $currentChurchId)->first();
        }
        
        // Fallback to primary church
        return $this->primaryChurch()->first();
    }

    /**
     * Set the current active church
     */
    public function setCurrentChurch($churchId)
    {
        // Vérifier que l'utilisateur a accès à cette église
        if ($this->hasAccessToChurch($churchId)) {
            session(['current_church_id' => $churchId]);
            return true;
        }
        return false;
    }

    /**
     * Check if user has access to a specific church
     */
    public function hasAccessToChurch($churchId): bool
    {
        return $this->churches()->where('churches.id', $churchId)
            ->wherePivot('is_active', true)
            ->exists();
    }


    /**
     * Get the role that the user has
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Church admin has all permissions
        if ($this->is_church_admin) {
            return true;
        }

        // Check role permissions
        if ($this->role && $this->role->hasPermission($permission)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can perform an action on a module
     */
    public function canPerform(string $action, string $module): bool
    {
        $permission = $module . '.' . $action;
        return $this->hasPermission($permission);
    }

    /**
     * Check if user is admin of their church
     */
    public function isChurchAdmin(): bool
    {
        return $this->is_church_admin;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get user's permissions
     */
    public function getPermissions(): array
    {
        if ($this->is_church_admin) {
            return array_keys(Role::getAvailablePermissions());
        }

        return $this->role ? $this->role->permissions : [];
    }

    /**
     * Scope to filter users by church
     */
    public function scopeForChurch($query, $churchId)
    {
        return $query->whereHas('churches', function ($q) use ($churchId) {
            $q->where('church_id', $churchId)->where('is_active', true);
        });
    }

    /**
     * Scope to filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
