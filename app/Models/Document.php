<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Traits\BelongsToChurch;

class Document extends Model
{
    use HasFactory, BelongsToChurch;

    protected $fillable = [
        'name',
        'original_name',
        'file_path',
        'file_url',
        'file_type',
        'file_size',
        'mime_type',
        'description',
        'folder_id',
        'is_public',
        'church_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'file_size' => 'integer'
    ];

    // Relations
    public function folder()
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
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
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeImages($query)
    {
        return $query->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function scopePdfs($query)
    {
        return $query->where('mime_type', 'application/pdf');
    }

    public function scopeByFolder($query, $folderId)
    {
        return $query->where('folder_id', $folderId);
    }

    // Accessors
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        switch ($this->file_type) {
            case 'image':
                return 'mdi-image';
            case 'pdf':
                return 'mdi-file-pdf-box';
            case 'word':
                return 'mdi-file-word-box';
            case 'excel':
                return 'mdi-file-excel-box';
            case 'powerpoint':
                return 'mdi-file-powerpoint-box';
            case 'video':
                return 'mdi-file-video';
            case 'audio':
                return 'mdi-file-music';
            case 'archive':
                return 'mdi-file-zip-box';
            default:
                return 'mdi-file-document-outline';
        }
    }

    public function getFileColorAttribute()
    {
        switch ($this->file_type) {
            case 'image':
                return 'success';
            case 'pdf':
                return 'danger';
            case 'word':
                return 'primary';
            case 'excel':
                return 'success';
            case 'powerpoint':
                return 'warning';
            case 'video':
                return 'info';
            case 'audio':
                return 'purple';
            case 'archive':
                return 'secondary';
            default:
                return 'secondary';
        }
    }

    public function getFileExtensionAttribute()
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    public function getIsImageAttribute()
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function getIsPdfAttribute()
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->is_image) {
            // Pour les images, on peut utiliser l'URL Firebase avec des paramètres de redimensionnement
            return $this->file_url . '?w=200&h=200&fit=crop';
        }
        
        // Pour les PDFs, on peut générer une icône ou utiliser un service de thumbnail
        return '/images/pdf-icon.png';
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (Auth::check()) {
                $document->created_by = Auth::id();
                $document->updated_by = Auth::id();
            }
        });

        static::updating(function ($document) {
            if (Auth::check()) {
                $document->updated_by = Auth::id();
            }
        });
    }
}
