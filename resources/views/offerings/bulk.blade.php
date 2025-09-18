@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Saisie groupée des offrandes</h1>
        <a href="{{ route('offerings.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
    <form method="POST" action="{{ route('offerings.bulk.store') }}" class="card p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="received_at" value="{{ old('received_at', now()->format('Y-m-d')) }}" class="form-control @error('received_at') is-invalid @enderror" required>
                @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Méthode</label>
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
                <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror">
                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @foreach($types as $slug => $name)
                <div class="col-md-4">
                    <label class="form-label">Montant {{ $name }}</label>
                    <input type="number" step="0.01" name="amount_{{ $slug }}" value="{{ old('amount_'.$slug) }}" class="form-control @error('amount_'.$slug) is-invalid @enderror">
                    @error('amount_'.$slug)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Enregistrer</button>
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


