<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DocumentFolder extends Model
{
    use HasFactory, BelongsToChurch;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'sort_order',
        'church_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relations
    public function documents()
    {
        return $this->hasMany(Document::class, 'folder_id');
    }

    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getDocumentsCountAttribute()
    {
        return $this->documents()->count();
    }

    public function getTotalSizeAttribute()
    {
        return $this->documents()->sum('file_size');
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->total_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($folder) {
            if (auth()->check()) {
                $folder->created_by = auth()->id();
                $folder->updated_by = auth()->id();
            }
        });

        static::updating(function ($folder) {
            if (auth()->check()) {
                $folder->updated_by = auth()->id();
            }
        });
    }
}
