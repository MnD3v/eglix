<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchEvent extends Model
{
    protected $fillable = [
        'title','date','start_time','end_time','type','location','description','images'
    ];

    protected $casts = [
        'date' => 'date',
        'images' => 'array',
    ];
}
