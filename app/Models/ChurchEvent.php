<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchEvent extends Model
{
    protected $fillable = [
        'church_id', 'title','date','start_time','end_time','type','location','description','images'
    ];

    protected $casts = [
        'date' => 'date',
        'images' => 'array',
    ];

    /**
     * Relation avec l'église
     */
    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}
