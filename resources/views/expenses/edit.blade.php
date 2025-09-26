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

/* Styles pour les switches */
.form-check-input:checked {
    background-color: #FFCC00;
    border-color: #FFCC00;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 204, 0, 0.25);
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Dépense -->
    <div class="appbar expenses-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('expenses.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier Dépense</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('expenses.update', $expense) }}">
        @csrf
        @method('PUT')
        
        <!-- Section Informations de la Dépense -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dépense</h2>
            <p class="section-subtitle">Détails sur la dépense à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="hasProjectToggle" name="has_project" value="1" @checked(old('has_project', $expense->project_id ? 1 : 0))>
                        <label class="form-check-label fw-semibold" for="hasProjectToggle">Lier à un projet</label>
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
                        <input name="title" value="{{ old('title', $expense->project_id ? '' : $expense->description) }}" class="form-control @error('title') is-invalid @enderror" placeholder="Ex: Achat de matériel, Frais de transport...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de Paiement</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', optional($expense->paid_at)->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                    @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense->amount) }}" class="form-control @error('amount') is-invalid @enderror" required placeholder="Ex: 50000">
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
                        <option value="cash" @selected(old('payment_method', $expense->payment_method)==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method', $expense->payment_method)==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method', $expense->payment_method)==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" id="referenceField" style="display:none;">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference', $expense->reference) }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Ex: MTN-123456789">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur la dépense</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes complémentaires sur la dépense...">{{ old('notes', $expense->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
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
    // Gestion du toggle projet
    const hasProjectToggle = document.getElementById('hasProjectToggle');
    const projectSelectWrap = document.getElementById('projectSelectWrap');
    const titleWrap = document.getElementById('titleWrap');
    
    function toggleProjectFields() {
        if (hasProjectToggle.checked) {
            projectSelectWrap.style.display = 'block';
            titleWrap.style.display = 'none';
        } else {
            projectSelectWrap.style.display = 'none';
            titleWrap.style.display = 'block';
        }
    }
    
    // Gestion du champ référence
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
    
    // Initialisation
    toggleProjectFields();
    toggleReferenceField();
    
    // Event listeners
    hasProjectToggle.addEventListener('change', toggleProjectFields);
    paymentMethodSelect.addEventListener('change', toggleReferenceField);
});
</script>
@endsection


