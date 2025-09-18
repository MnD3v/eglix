<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'email',
        'password',
        'church_id',
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
     * Get the church that the user belongs to
     */
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
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
        return $query->where('church_id', $churchId);
    }

    /**
     * Scope to filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
