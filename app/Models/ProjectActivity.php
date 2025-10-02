<?php

namespace App\Models;

use App\Traits\BelongsToChurch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ProjectActivity extends Model
{
    use BelongsToChurch;

    protected $fillable = [
        'project_id',
        'title',
        'amount_spent',
        'description',
        'activity_date',
        'church_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'amount_spent' => 'decimal:2',
    ];

    /**
     * Boot du modèle pour auto-remplir les champs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            if (Auth::check()) {
                $activity->church_id = Auth::user()->church_id;
                $activity->created_by = Auth::id();
            }
        });

        static::updating(function ($activity) {
            if (Auth::check()) {
                $activity->updated_by = Auth::id();
            }
        });
    }

    /**
     * Relation avec le projet
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur modificateur
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope pour filtrer par projet
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope pour ordonner par date d'activité
     */
    public function scopeOrderByActivityDate($query, $direction = 'desc')
    {
        return $query->orderBy('activity_date', $direction);
    }
}
