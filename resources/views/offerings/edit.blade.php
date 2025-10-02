@extends('layouts.app')
@section('content')
<style>
/* Styles pour les boutons */
.btn-primary,
.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
    font-weight: 700 !important;
    border-radius: 12px;
    padding: 12px 24px;
    transition: all 0.3s ease;
}

.btn-primary:hover,
.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary i,
.btn-outline-secondary i {
    color: #000000 !important;
}

.btn-primary:hover i,
.btn-outline-secondary:hover i {
    color: #000000 !important;
}
</style>
<div class="container py-4">
    <h1 class="h3 mb-3">Modifier offrande</h1>
    <form method="POST" action="{{ route('offerings.update', $offering) }}" class="card p-3">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="received_at" value="{{ old('received_at', optional($offering->received_at)->format('Y-m-d')) }}" class="form-control @error('received_at') is-invalid @enderror" required>
                @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $offering->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Type</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                    @foreach($types as $t)
                        <option value="{{ $t }}" @selected(old('type', $offering->type)==$t)>{{ ucfirst(str_replace('_',' ', $t)) }}</option>
                    @endforeach
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Méthode</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="cash" @selected(old('payment_method', $offering->payment_method)==='cash')>Espèces</option>
                    <option value="mobile" @selected(old('payment_method', $offering->payment_method)==='mobile')>Mobile money</option>
                    <option value="bank" @selected(old('payment_method', $offering->payment_method)==='bank')>Banque</option>
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4" id="referenceField" style="display:none;">
                <label class="form-label">Référence</label>
                <input name="reference" value="{{ old('reference', $offering->reference) }}" class="form-control @error('reference') is-invalid @enderror">
                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $offering->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('offerings.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn">Enregistrer</button>
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
    })();
    </script>
</div>
@endsection


