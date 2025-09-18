<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'date','theme','type','preacher','choir','start_time','end_time','location','notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(ServiceAssignment::class);
    }
}
