<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToChurch;
use Illuminate\Support\Facades\Auth;

class Guest extends Model
{
    use BelongsToChurch;

    protected $fillable = [
        'church_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'visit_date',
        'origin',
        'referral_source',
        'church_background',
        'spiritual_status',
        'spiritual_notes',
        'status',
        'notes',
        'welcomed_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($guest) {
            if (Auth::check()) {
                $guest->church_id = Auth::user()->church_id;
                $guest->created_by = Auth::id();
            }
        });
        
        static::updating(function ($guest) {
            if (Auth::check()) {
                $guest->updated_by = Auth::id();
            }
        });
    }

    /**
     * Relation avec l'église
     */
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    /**
     * Relation avec l'utilisateur qui a accueilli l'invité
     */
    public function welcomedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'welcomed_by');
    }

    /**
     * Relation avec l'utilisateur qui a créé l'enregistrement
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a mis à jour l'enregistrement
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope pour filtrer par mois
     */
    public function scopeForMonth($query, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        return $query->whereYear('visit_date', $year)
                    ->whereMonth('visit_date', $month);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour les nouveaux visiteurs (première visite)
     */
    public function scopeFirstTime($query)
    {
        return $query->where('status', 'visit_1');
    }

    /**
     * Scope pour les visiteurs réguliers
     */
    public function scopeReturning($query)
    {
        return $query->whereIn('status', ['visit_2_3', 'returning']);
    }

    /**
     * Scope pour les conversions en membres
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'member_converted');
    }

    /**
     * Accessor pour le nom complet
     */
    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    /**
     * Accessor pour le statut traduit
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'visit_1' => 'Première visite',
            'visit_2_3' => '2ème-3ème visite',
            'returning' => 'Visiteur régulier',
            'member_converted' => 'Devenu membre',
            'no_longer_interested' => 'Plus intéressé',
            default => 'Première visite',
        };
    }

    /**
     * Accessor pour l'origine traduite
     */
    public function getOriginLabelAttribute(): string
    {
        return match($this->origin) {
            'referral' => 'Invitation',
            'social_media' => 'Réseaux sociaux',
            'event' => 'Événement',
            'walk_in' => 'Visite spontanée',
            'flyer' => 'Flyer',
            'other' => 'Autre',
            default => 'Visite spontanée',
        };
    }

    /**
     * Méthode statique pour obtenir les types d'origine disponibles
     */
    public static function getOriginTypes(): array
    {
        return [
            'referral' => 'Invitation',
            'social_media' => 'Réseaux sociaux', 
            'event' => 'Événement',
            'walk_in' => 'Visite spontanée',
            'flyer' => 'Flyer',
            'other' => 'Autre',
        ];
    }

    /**
     * Méthode statique pour obtenir les statuts disponibles
     */
    public static function getStatusTypes(): array
    {
        return [
            'visit_1' => 'Première visite',
            'visit_2_3' => '2ème-3ème visite',
            'returning' => 'Visiteur régulier',
            'member_converted' => 'Devenu membre',
            'no_longer_interested' => 'Plus intéressé',
        ];
    }

    /**
     * Méthode pour obtenir les statistiques mensuelles
     */
    public static function getMonthlyStats($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        $startDate = now()->setYear($year)->setMonth($month)->startOfMonth();
        $endDate = now()->setYear($year)->setMonth($month)->endOfMonth();
        
        return self::whereBetween('visit_date', [$startDate, $endDate])
                  ->where('church_id', Auth::user()->church_id)
                  ->selectRaw('
                      COUNT(*) as total_guests,
                      COUNT(CASE WHEN status = ? THEN 1 END) as first_time,
                      COUNT(CASE WHEN status = ? THEN 1 END) as return_visits,
                      COUNT(CASE WHEN status = ? THEN 1 END) as conversions
                  ', ['visit_1', 'visit_2_3', 'member_converted'])->first();
    }
}
