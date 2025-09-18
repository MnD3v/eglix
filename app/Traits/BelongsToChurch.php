<?php

namespace App\Traits;

trait BelongsToChurch
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToChurch()
    {
        static::creating(function ($model) {
            // Si church_id n'est pas déjà défini, essayer de l'obtenir de l'utilisateur authentifié
            if (empty($model->church_id) && auth()->check() && auth()->user()->church_id) {
                $model->church_id = auth()->user()->church_id;
            }
        });
    }

    /**
     * Scope to filter by church
     */
    public function scopeForChurch($query, $churchId = null)
    {
        $churchId = $churchId ?? auth()->user()?->church_id;
        
        if ($churchId) {
            return $query->where('church_id', $churchId);
        }
        
        return $query;
    }

    /**
     * Get the church that owns the model
     */
    public function church()
    {
        return $this->belongsTo(\App\Models\Church::class);
    }
}
