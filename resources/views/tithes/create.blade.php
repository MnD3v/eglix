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
    <!-- AppBar Nouvelle Dîme -->
    <div class="appbar tithes-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('tithes.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Dîme</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('tithes.store') }}">
        @csrf
        
        <!-- Section Informations de la Dîme -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dîme</h2>
            <p class="section-subtitle">Détails sur le membre et le montant de la dîme</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Membre</label>
                    <select name="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                        <option value="">Rechercher un membre...</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" @selected(old('member_id', request('member_id'))==$m->id)>
                                {{ $m->last_name }} {{ $m->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de Paiement</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                    @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required placeholder="0.00">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Méthode de Paiement -->
        <div class="form-section">
            <h2 class="section-title">Méthode de Paiement</h2>
            <p class="section-subtitle">Détails sur le mode de paiement utilisé</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Méthode de Paiement</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="cash" @selected(old('payment_method')==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method')==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method')==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" id="referenceField" style="display:none;">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Numéro de transaction ou référence">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur cette dîme</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Ajoutez des notes ou commentaires sur cette dîme...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-3 justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer la Dîme
            </button>
        </div>
    </form>
    <script>
    (function(){
        const pm = document.querySelector('select[name="payment_method"]');
        const refWrap = document.getElementById('referenceField');
        function updateRef(){
            const val = pm ? pm.value : '';
            const show = val === 'mobile' || val === 'bank';
            if (refWrap) refWrap.style.display = show ? '' : 'none';
        }
        if (pm) {
            pm.addEventListener('change', updateRef);
            updateRef();
        }
        
        // Configuration Select2 simple
        $(document).ready(function() {
            $('select[name="member_id"]').select2({
                placeholder: "Rechercher un membre...",
                allowClear: false,
                width: '100%'
            });
        });
        
    })();
    </script>
</div>
@endsection


