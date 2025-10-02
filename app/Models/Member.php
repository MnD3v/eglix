<?php

namespace App\Models;

use App\Traits\BelongsToChurch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Member extends Model
{
    use BelongsToChurch;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'marital_status',
        'function',
        'profile_photo',
        'photo_url',
        'birth_date',
        'baptized_at',
        'baptism_responsible',
        'status',
        'joined_at',
        'notes',
        'remarks',
        'church_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'baptized_at' => 'date',
        'joined_at' => 'date',
        'remarks' => 'array',
    ];

    public function tithes(): HasMany
    {
        return $this->hasMany(Tithe::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Ajouter une remarque au membre
     */
    public function addRemark(string $remark, string $type = 'general'): void
    {
        $remarks = $this->remarks ?? [];
        $remarks[] = [
            'remark' => $remark,
            'type' => $type,
            'added_at' => now()->toISOString(),
            'added_by' => Auth::id(),
        ];
        $this->update(['remarks' => $remarks]);
    }

    /**
     * Supprimer une remarque par son index
     */
    public function removeRemark(int $index): void
    {
        $remarks = $this->remarks ?? [];
        if (isset($remarks[$index])) {
            unset($remarks[$index]);
            $this->update(['remarks' => array_values($remarks)]);
        }
    }

    /**
     * Obtenir les remarques formatées
     */
    public function getFormattedRemarks(): array
    {
        $remarks = $this->remarks ?? [];
        return array_map(function ($remark) {
            return [
                'remark' => $remark['remark'] ?? $remark,
                'type' => $remark['type'] ?? 'general',
                'type_label' => $this->getRemarkTypeLabel($remark['type'] ?? 'general'),
                'type_color' => $this->getRemarkTypeColor($remark['type'] ?? 'general'),
                'added_at' => isset($remark['added_at']) ? \Carbon\Carbon::parse($remark['added_at'])->format('d/m/Y H:i') : 'Date inconnue',
                'added_by' => isset($remark['added_by']) ? User::find($remark['added_by'])?->name : 'Utilisateur inconnu',
            ];
        }, $remarks);
    }

    /**
     * Obtenir le libellé du type de remarque
     */
    public function getRemarkTypeLabel(string $type): string
    {
        return match($type) {
            'spiritual' => 'Spirituel',
            'positive' => 'Bonne remarque',
            'negative' => 'Mauvaise remarque',
            'disciplinary' => 'Disciplinaire',
            'pastoral' => 'Pastoral',
            'general' => 'Général',
            default => 'Général',
        };
    }

    /**
     * Obtenir la couleur du type de remarque
     */
    public function getRemarkTypeColor(string $type): string
    {
        return match($type) {
            'spiritual' => '#8B5CF6', // Violet
            'positive' => '#10B981', // Vert
            'negative' => '#EF4444', // Rouge
            'disciplinary' => '#F59E0B', // Orange
            'pastoral' => '#3B82F6', // Bleu
            'general' => '#6B7280', // Gris
            default => '#6B7280',
        };
    }

    /**
     * Obtenir tous les types de remarques disponibles
     */
    public static function getRemarkTypes(): array
    {
        return [
            'general' => 'Général',
            'spiritual' => 'Spirituel',
            'positive' => 'Bonne remarque',
            'negative' => 'Mauvaise remarque',
            'disciplinary' => 'Disciplinaire',
            'pastoral' => 'Pastoral',
        ];
    }
}
