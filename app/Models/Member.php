<?php

namespace App\Models;

use App\Traits\BelongsToChurch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'joined_at',
        'notes',
        'remarks',
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
    public function addRemark(string $remark): void
    {
        $remarks = $this->remarks ?? [];
        $remarks[] = [
            'remark' => $remark,
            'added_at' => now()->toISOString(),
            'added_by' => auth()->id(),
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
                'added_at' => isset($remark['added_at']) ? \Carbon\Carbon::parse($remark['added_at'])->format('d/m/Y H:i') : 'Date inconnue',
                'added_by' => isset($remark['added_by']) ? User::find($remark['added_by'])?->name : 'Utilisateur inconnu',
            ];
        }, $remarks);
    }
}
