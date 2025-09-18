<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'occurred_at', 'description',
    ];

    protected $casts = [
        'occurred_at' => 'date',
    ];

    public function images()
    {
        return $this->hasMany(JournalImage::class);
    }
}


