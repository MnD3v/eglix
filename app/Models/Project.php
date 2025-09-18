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
}
