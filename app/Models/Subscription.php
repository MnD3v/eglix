<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'church_id',
        'amount',
        'currency',
        'plan_name',
        'max_members',
        'has_advanced_reports',
        'has_api_access',
        'start_date',
        'end_date',
        'payment_date',
        'payment_status',
        'payment_method',
        'is_active',
        'notes',
        'receipt_number',
        'payment_reference',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'has_advanced_reports' => 'boolean',
        'has_api_access' => 'boolean',
    ];

    // Relations
    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('is_active', 'expired');
    }

    public function scopeSuspended($query)
    {
        return $query->where('is_active', 'suspended');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    // Accessors & Mutators
    public function getIsValidAttribute(): bool
    {
        return $this->is_active === 'active' && 
               $this->end_date >= now() && 
               $this->payment_status === 'paid';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date < now() || $this->is_active === 'expired';
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format((float) $this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    // Méthodes utilitaires
    public function renew(int $months = 12): self
    {
        $this->update([
            'end_date' => $this->end_date->addMonths($months),
            'payment_status' => 'pending',
            'payment_date' => null,
            'is_active' => 'active',
            'updated_by' => auth()->id() ?? null
        ]);

        return $this;
    }

    public function markAsPaid(string $paymentMethod = 'cash', string $receiptNumber = null): self
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'receipt_number' => $receiptNumber,
            'is_active' => 'active',
            'updated_by' => auth()->id() ?? null
        ]);

        return $this;
    }

    public function suspend(string $reason = null): self
    {
        $this->update([
            'is_active' => 'suspended',
            'notes' => $this->notes . "\nSuspendu le " . now()->format('d/m/Y') . ": " . $reason,
            'updated_by' => auth()->id() ?? null
        ]);

        return $this;
    }

    public function expire(): self
    {
        $this->update([
            'is_active' => 'expired',
            'notes' => $this->notes . "\nExpiré le " . now()->format('d/m/Y'),
            'updated_by' => auth()->id() ?? null
        ]);

        return $this;
    }

    // Méthodes statiques
    public static function getPlans(): array
    {
        return [
            'basic' => 'Basique',
            'premium' => 'Premium',
            'enterprise' => 'Entreprise'
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'active' => 'Actif',
            'expired' => 'Expiré',
            'suspended' => 'Suspendu'
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return [
            'pending' => 'En attente',
            'paid' => 'Payé',
            'overdue' => 'En retard',
            'cancelled' => 'Annulé'
        ];
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Espèces',
            'bank_transfer' => 'Virement bancaire',
            'mobile_money' => 'Mobile Money',
            'check' => 'Chèque'
        ];
    }

    // Méthode pour vérifier l'accès à la plateforme
    public static function checkChurchAccess(int $churchId): bool
    {
        $subscription = self::where('church_id', $churchId)
            ->where('is_active', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>=', now())
            ->first();

        return $subscription !== null;
    }

    // Méthode pour obtenir l'abonnement actuel d'une église
    public static function getCurrentSubscription(int $churchId): ?self
    {
        return self::where('church_id', $churchId)
            ->where('is_active', 'active')
            ->where('payment_status', 'paid')
            ->where('end_date', '>=', now())
            ->first();
    }
}
