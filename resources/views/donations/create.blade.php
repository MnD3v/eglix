@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 m-0">Nouveau don</h1>
        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
    <form method="POST" action="{{ route('donations.store') }}" class="card card-soft p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Membre</label>
                <select name="member_id" class="form-select @error('member_id') is-invalid @enderror">
                    <option value="">—</option>
                    @foreach($members as $m)
                        <option value="{{ $m->id }}" @selected(old('member_id')==$m->id)>{{ $m->last_name }} {{ $m->first_name }}</option>
                    @endforeach
                </select>
                @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label d-flex align-items-center justify-content-between">
                    <span>Projet</span>
                    <span class="form-check form-switch m-0">
                        @php $withProject = old('project_id') ? true : false; @endphp
                        <input class="form-check-input" type="checkbox" id="toggleProject" {{ $withProject ? 'checked' : '' }}>
                    </span>
                </label>
                <select name="project_id" id="projectSelect" class="form-select @error('project_id') is-invalid @enderror" style="display:none;">
                    <option value="">—</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected(old('project_id')==$p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="received_at" value="{{ old('received_at', now()->format('Y-m-d')) }}" class="form-control @error('received_at') is-invalid @enderror" required>
                @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <!-- Toggle pour le type de don -->
            <div class="col-12">
                <label class="form-label fw-semibold">Type de don *</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="donation_type" id="donation_type_money" value="money" {{ old('donation_type', 'money') == 'money' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="donation_type_money">
                        <i class="bi bi-cash-coin me-2"></i>Argent
                    </label>
                    
                    <input type="radio" class="btn-check" name="donation_type" id="donation_type_physical" value="physical" {{ old('donation_type') == 'physical' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="donation_type_physical">
                        <i class="bi bi-box me-2"></i>Objet physique
                    </label>
                </div>
                @error('donation_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <!-- Champs pour l'argent -->
            <div class="col-md-3" id="amount-field">
                <label class="form-label">Montant (FCFA)</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror">
                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3" id="payment-method-field">
                <label class="form-label">Méthode de paiement</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                    <option value="">—</option>
                    <option value="cash" @selected(old('payment_method')==='cash')>Espèces</option>
                    <option value="mobile" @selected(old('payment_method')==='mobile')>Mobile money</option>
                    <option value="bank" @selected(old('payment_method')==='bank')>Banque</option>
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Champs pour l'objet physique -->
            <div class="col-md-6" id="physical-item-field" style="display: none;">
                <label class="form-label">Objet donné</label>
                <input type="text" name="physical_item" value="{{ old('physical_item') }}" class="form-control @error('physical_item') is-invalid @enderror" placeholder="Ex: Livres, vêtements, nourriture...">
                @error('physical_item')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12" id="physical-description-field" style="display: none;">
                <label class="form-label">Description de l'objet</label>
                <textarea name="physical_description" class="form-control @error('physical_description') is-invalid @enderror" rows="2" placeholder="Décrivez l'objet donné...">{{ old('physical_description') }}</textarea>
                @error('physical_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Nom du donateur (si externe)</label>
                <input name="donor_name" value="{{ old('donor_name') }}" class="form-control @error('donor_name') is-invalid @enderror">
                @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4" id="reference-field">
                <label class="form-label">Référence</label>
                <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror">
                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button class="btn btn-primary">Enregistrer</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moneyRadio = document.getElementById('donation_type_money');
    const physicalRadio = document.getElementById('donation_type_physical');
    const amountField = document.getElementById('amount-field');
    const paymentMethodField = document.getElementById('payment-method-field');
    const physicalItemField = document.getElementById('physical-item-field');
    const physicalDescriptionField = document.getElementById('physical-description-field');
    const referenceField = document.getElementById('reference-field');

    function toggleFields() {
        if (moneyRadio.checked) {
            amountField.style.display = 'block';
            paymentMethodField.style.display = 'block';
            physicalItemField.style.display = 'none';
            physicalDescriptionField.style.display = 'none';
            referenceField.style.display = 'block';
        } else if (physicalRadio.checked) {
            amountField.style.display = 'none';
            paymentMethodField.style.display = 'none';
            physicalItemField.style.display = 'block';
            physicalDescriptionField.style.display = 'block';
            referenceField.style.display = 'block';
        }
    }

    moneyRadio.addEventListener('change', toggleFields);
    physicalRadio.addEventListener('change', toggleFields);
    
    // Initialiser l'affichage
    toggleFields();
    // Toggle project select
    const toggle = document.getElementById('toggleProject');
    const projectSelect = document.getElementById('projectSelect');
    function updateProject(){ projectSelect.style.display = toggle.checked ? '' : 'none'; if (!toggle.checked) { projectSelect.value=''; } }
    if (toggle && projectSelect) { toggle.addEventListener('change', updateProject); updateProject(); }
});
</script>
@endsection


