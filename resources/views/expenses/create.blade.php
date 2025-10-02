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

.btn-primary,
.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
    font-weight: 700 !important;
}

.btn-primary:hover,
.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary i,
.btn-outline-secondary i {
    color: #000000 !important;
}

.btn-primary:hover i,
.btn-outline-secondary:hover i {
    color: #000000 !important;
}

/* Styles pour les switches */
.form-check-input {
    border-radius: 6px;
    border: 2px solid #e2e8f0;
}

.form-check-input:checked {
    background-color: #FFCC00;
    border-color: #FFCC00;
}

.form-check-label {
    font-weight: 500;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>
<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouvelle Dépense -->
    <div class="appbar expenses-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('expenses.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Dépense</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf
        
        <!-- Section Projet -->
        <div class="form-section">
            <h2 class="section-title">Projet</h2>
            <p class="section-subtitle">Lier cette dépense à un projet spécifique</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="hasProjectToggle" name="has_project" value="1" @checked(old('has_project', old('project_id') ? 1 : 0))>
                        <label class="form-check-label" for="hasProjectToggle">
                            <i class="bi bi-folder-plus me-2"></i>Lier à un projet
                        </label>
                    </div>
                    <div id="projectSelectWrap" style="display:none;">
                        <label class="form-label">Projet</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id')==$p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div id="titleWrap" style="display:none;">
                        <label class="form-label">Titre de la Dépense</label>
                        <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Ex: Achat de matériel, Frais de transport...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Informations de la Dépense -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dépense</h2>
            <p class="section-subtitle">Détails sur le montant et la méthode de paiement</p>
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date de Paiement</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                    @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required placeholder="0.00">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Méthode de Paiement</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="cash" @selected(old('payment_method')==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method')==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method')==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3" id="referenceField" style="display:none;">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Numéro de transaction ou référence">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="form-section">
            <h2 class="section-title">Description</h2>
            <p class="section-subtitle">Détails sur la nature de cette dépense</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <input name="description" value="{{ old('description') }}" class="form-control @error('description') is-invalid @enderror" placeholder="Ex: Achat de matériel informatique, Frais de transport pour l'événement...">
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur cette dépense</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Ajoutez des notes ou commentaires sur cette dépense...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-3 justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer la Dépense
            </button>
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


