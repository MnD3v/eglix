@extends('layouts.app')

@push('scripts')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Nouvelle dîme</h1>
    <form method="POST" action="{{ route('tithes.store') }}" class="card p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Membre</label>
                <select name="member_id" class="form-select select2-members @error('member_id') is-invalid @enderror" required>
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
                <label class="form-label">Date</label>
                <input type="date" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Méthode</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="cash" @selected(old('payment_method')==='cash')>Espèces</option>
                    <option value="mobile" @selected(old('payment_method')==='mobile')>Mobile money</option>
                    <option value="bank" @selected(old('payment_method')==='bank')>Banque</option>
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4" id="referenceField" style="display:none;">
                <label class="form-label">Référence</label>
                <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror">
                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('tithes.index') }}" class="btn btn-outline-secondary">Annuler</a>
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

        // Initialisation de Select2 pour la recherche de membres
        $(document).ready(function() {
            $('.select2-members').select2({
                placeholder: 'Rechercher un membre...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    },
                    searching: function() {
                        return "Recherche en cours...";
                    }
                }
            });
        });
    })();
    </script>
</div>
@endsection


