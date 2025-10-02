<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToChurch;

class Project extends Model
{
    use BelongsToChurch;

    protected $fillable = [
        'church_id','name','description','start_date','end_date','budget','status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class);
    }

    /**
     * Obtenir le total des montants dépensés dans les activités
     */
    public function getTotalActivitiesSpentAttribute()
    {
        return $this->activities()->sum('amount_spent');
    }

    /**
     * Obtenir le nombre d'activités réalisées
     */
    public function getActivitiesCountAttribute()
    {
        return $this->activities()->count();
    }
}
