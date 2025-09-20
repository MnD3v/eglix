@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h1 class="h3 mb-3">Modifier dépense</h1>
    <form method="POST" action="{{ route('expenses.update', $expense) }}" class="card p-3">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="hasProjectToggle" name="has_project" value="1" @checked(old('has_project', $expense->project_id ? 1 : 0))>
                    <label class="form-check-label" for="hasProjectToggle">Lier à un projet</label>
                </div>
                <div id="projectSelectWrap" style="display:none;">
                    <label class="form-label">Projet</label>
                    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                        <option value="">—</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" @selected(old('project_id', $expense->project_id)==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div id="titleWrap" style="display:none;">
                    <label class="form-label">Titre de la dépense</label>
                    <input name="title" value="{{ old('title', $expense->project_id ? '' : $expense->description) }}" class="form-control @error('title') is-invalid @enderror">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="paid_at" value="{{ old('paid_at', optional($expense->paid_at)->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Méthode</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="cash" @selected(old('payment_method', $expense->payment_method)==='cash')>Espèces</option>
                    <option value="mobile" @selected(old('payment_method', $expense->payment_method)==='mobile')>Mobile money</option>
                    <option value="bank" @selected(old('payment_method', $expense->payment_method)==='bank')>Banque</option>
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4" id="referenceField" style="display:none;">
                <label class="form-label">Référence</label>
                <input name="reference" value="{{ old('reference', $expense->reference) }}" class="form-control @error('reference') is-invalid @enderror">
                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <input name="description" value="{{ old('description', $expense->description) }}" class="form-control @error('description') is-invalid @enderror">
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn">Enregistrer</button>
        </div>
    </form>
    <script>
    (function(){
        const toggle = document.getElementById('hasProjectToggle');
        const wrap = document.getElementById('projectSelectWrap');
        const titleWrap = document.getElementById('titleWrap');
        function update(){
            if (!toggle || !wrap) return;
            const on = !!toggle.checked;
            wrap.style.display = on ? '' : 'none';
            if (titleWrap) titleWrap.style.display = on ? 'none' : '';
            if (!toggle.checked) {
                const sel = wrap.querySelector('select[name="project_id"]');
                if (sel) sel.value = '';
            }
        }
        if (toggle) {
            toggle.addEventListener('change', update);
            update();
        }
        // Reference visibility based on payment method
        const pm = document.querySelector('select[name="payment_method"]');
        const refWrap = document.getElementById('referenceField');
        function updateRef(){
            const val = pm ? pm.value : '';
            const show = val === 'mobile' || val === 'bank';
            if (refWrap) refWrap.style.display = show ? '' : 'none';
            if (!show) {
                const ref = refWrap ? refWrap.querySelector('input[name="reference"]') : null;
                if (ref) ref.value = '';
            }
        }
        if (pm) {
            pm.addEventListener('change', updateRef);
            updateRef();
        }
    })();
    </script>
</div>
@endsection


