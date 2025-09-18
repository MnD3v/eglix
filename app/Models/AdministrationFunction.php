<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdministrationFunction extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'function_name',
        'start_date',
        'end_date',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relations
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    // Accessors
    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'Inactif';
        }

        if ($this->end_date && $this->end_date < now()) {
            return 'Terminé';
        }

        return 'Actif';
    }

    public function getDurationAttribute()
    {
        $end = $this->end_date ?? now();
        return $this->start_date->diffInDays($end);
    }
}