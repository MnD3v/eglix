@extends('layouts.app')

@section('content')
<style>
/* Styles pour les champs de formulaire arrondis */
.form-control, .form-select, .form-label {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.form-control:focus, .form-select:focus {
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles pour les sections du formulaire */
.form-section {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.section-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* Styles pour les boutons */
.btn {
    border-radius: 12px;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Dîme -->
    <div class="appbar tithes-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('tithes.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier Dîme</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('tithes.update', $tithe) }}">
        @csrf
        @method('PUT')
        
        <!-- Section Informations de la Dîme -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dîme</h2>
            <p class="section-subtitle">Détails sur la dîme à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Membre</label>
                    <select name="member_id" class="form-select select2-members @error('member_id') is-invalid @enderror" required>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" @selected(old('member_id', $tithe->member_id)==$m->id)>
                                {{ $m->last_name }} {{ $m->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de Paiement</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', optional($tithe->paid_at)->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                    @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $tithe->amount) }}" class="form-control @error('amount') is-invalid @enderror" required placeholder="Ex: 5000">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Paiement -->
        <div class="form-section">
            <h2 class="section-title">Informations de Paiement</h2>
            <p class="section-subtitle">Méthode et détails du paiement</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Méthode de Paiement</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="cash" @selected(old('payment_method', $tithe->payment_method)==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method', $tithe->payment_method)==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method', $tithe->payment_method)==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" id="referenceField" style="display:none;">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference', $tithe->reference) }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Ex: MTN-123456789">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur la dîme</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes complémentaires sur la dîme...">{{ old('notes', $tithe->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('tithes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    const referenceField = document.getElementById('referenceField');
    
    function toggleReferenceField() {
        const selectedMethod = paymentMethodSelect.value;
        if (selectedMethod === 'mobile' || selectedMethod === 'bank') {
            referenceField.style.display = 'block';
        } else {
            referenceField.style.display = 'none';
        }
    }
    
    // Initial check
    toggleReferenceField();
    
    // Listen for changes
    paymentMethodSelect.addEventListener('change', toggleReferenceField);
});
</script>
@endsection


