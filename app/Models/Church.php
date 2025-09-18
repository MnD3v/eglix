<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Church extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'settings',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Boot method to generate slug automatically
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($church) {
            if (empty($church->slug)) {
                $baseSlug = Str::slug($church->name);
                $slug = $baseSlug;
                $counter = 1;
                
                // Ensure slug is unique (only if table exists)
                try {
                    while (static::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist yet, use base slug
                    $slug = $baseSlug;
                }
                
                $church->slug = $slug;
            }
        });
    }

    /**
     * Get the users for the church
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the roles for the church
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get the members for the church
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the tithes for the church
     */
    public function tithes(): HasMany
    {
        return $this->hasMany(Tithe::class);
    }

    /**
     * Get the offerings for the church
     */
    public function offerings(): HasMany
    {
        return $this->hasMany(Offering::class);
    }

    /**
     * Get the donations for the church
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the expenses for the church
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the projects for the church
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the services for the church
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the church events for the church
     */
    public function churchEvents(): HasMany
    {
        return $this->hasMany(ChurchEvent::class);
    }

    /**
     * Get the offering types for the church
     */
    public function offeringTypes(): HasMany
    {
        return $this->hasMany(OfferingType::class);
    }

    /**
     * Get the administration function types for the church
     */
    public function administrationFunctionTypes(): HasMany
    {
        return $this->hasMany(AdministrationFunctionType::class);
    }

    /**
     * Get the administration records for the church
     */
    public function administration(): HasMany
    {
        return $this->hasMany(Administration::class);
    }

    /**
     * Get the journal entries for the church
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Get the church admin user
     */
    public function admin()
    {
        return $this->users()->where('is_church_admin', true)->first();
    }

    /**
     * Check if user is admin of this church
     */
    public function isAdmin(User $user): bool
    {
        return $this->users()
            ->where('id', $user->id)
            ->where('is_church_admin', true)
            ->exists();
    }
}