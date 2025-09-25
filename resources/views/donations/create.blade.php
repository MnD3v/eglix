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
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="externalDonorToggle" name="external_donor" value="1" @checked(old('external_donor'))>
                    <label class="form-check-label" for="externalDonorToggle">Donateur externe</label>
                </div>
                <div id="memberSelectWrap">
                    <label class="form-label">Membre</label>
                    <select name="member_id" class="form-select @error('member_id') is-invalid @enderror">
                        <option value="">—</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}" @selected(old('member_id')==$m->id)>{{ $m->last_name }} {{ $m->first_name }}</option>
                        @endforeach
                    </select>
                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div id="externalDonorWrap" style="display:none;">
                    <label class="form-label">Nom du donateur externe</label>
                    <input name="donor_name" value="{{ old('donor_name') }}" class="form-control @error('donor_name') is-invalid @enderror" placeholder="Ex: Jean Dupont, Entreprise ABC...">
                    @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="hasProjectToggle" name="has_project" value="1" @checked(old('has_project', old('project_id') ? 1 : 0))>
                    <label class="form-check-label" for="hasProjectToggle">Lier à un projet</label>
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
                    <label class="form-label">Titre du don</label>
                    <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Ex: Don pour l'église, Offrande spéciale...">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
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
                    <label class="btn btn-outline-primary donation-type-btn" for="donation_type_money">
                        <i class="bi bi-cash-coin me-2"></i>Argent
                    </label>
                    
                    <input type="radio" class="btn-check" name="donation_type" id="donation_type_physical" value="physical" {{ old('donation_type') == 'physical' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary donation-type-btn" for="donation_type_physical">
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
            <button type="submit" class="btn btn">
                <span class="btn-text">Enregistrer</span>
            </button>
        </div>
    </form>
</div>

<style>
.donation-type-btn {
    background-color: white !important;
    border: 2px solid #dee2e6 !important;
    color: #6c757d !important;
    transition: all 0.3s ease;
}

.donation-type-btn:hover {
    background-color: #f8f9fa !important;
    border-color: #adb5bd !important;
    color: #495057 !important;
}

.btn-check:checked + .donation-type-btn {
    background-color: #ff2600 !important;
    border-color: #ff2600 !important;
    color: white !important;
}

.btn-check:checked + .donation-type-btn:hover {
    background-color: #e02200 !important;
    border-color: #e02200 !important;
}
</style>

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
            updateReferenceField();
        } else if (physicalRadio.checked) {
            amountField.style.display = 'none';
            paymentMethodField.style.display = 'none';
            physicalItemField.style.display = 'block';
            physicalDescriptionField.style.display = 'block';
            // Pour les objets physiques, toujours masquer le champ référence
            referenceField.style.display = 'none';
        }
    }

    function updateReferenceField() {
        const paymentMethod = document.querySelector('select[name="payment_method"]');
        
        if (paymentMethod) {
            const showReference = paymentMethod.value === 'mobile' || paymentMethod.value === 'bank';
            
            if (showReference) {
                referenceField.style.display = 'block';
            } else {
                referenceField.style.display = 'none';
                // Vider le champ quand il est masqué
                const referenceInput = referenceField.querySelector('input[name="reference"]');
                if (referenceInput) referenceInput.value = '';
            }
        } else {
            // Par défaut, masquer le champ référence
            referenceField.style.display = 'none';
        }
    }

    moneyRadio.addEventListener('change', toggleFields);
    physicalRadio.addEventListener('change', toggleFields);
    
    // Ajouter un event listener pour le changement de mode de paiement
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', updateReferenceField);
    }
    
    // Initialiser l'affichage
    toggleFields();
    
    // Initialiser le champ référence selon l'état initial avec un délai
    setTimeout(function() {
        updateReferenceField();
        // Déclencher l'événement change pour forcer la mise à jour (comme setState en Flutter)
        const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
        if (paymentMethodSelect) {
            paymentMethodSelect.dispatchEvent(new Event('change'));
        }
    }, 100);
    
    // Toggle external donor
    const externalDonorToggle = document.getElementById('externalDonorToggle');
    const memberSelectWrap = document.getElementById('memberSelectWrap');
    const externalDonorWrap = document.getElementById('externalDonorWrap');
    
    function updateExternalDonor() {
        if (!externalDonorToggle || !memberSelectWrap || !externalDonorWrap) return;
        const isExternal = !!externalDonorToggle.checked;
        memberSelectWrap.style.display = isExternal ? 'none' : '';
        externalDonorWrap.style.display = isExternal ? '' : 'none';
        
        if (isExternal) {
            const memberSelect = memberSelectWrap.querySelector('select[name="member_id"]');
            if (memberSelect) memberSelect.value = '';
        } else {
            const donorInput = externalDonorWrap.querySelector('input[name="donor_name"]');
            if (donorInput) donorInput.value = '';
        }
    }
    
    if (externalDonorToggle) {
        externalDonorToggle.addEventListener('change', updateExternalDonor);
        updateExternalDonor();
    }
    
    // Toggle project select
    const toggle = document.getElementById('hasProjectToggle');
    const projectSelectWrap = document.getElementById('projectSelectWrap');
    const titleWrap = document.getElementById('titleWrap');
    
    function updateProject() {
        if (!toggle || !projectSelectWrap || !titleWrap) return;
        const on = !!toggle.checked;
        projectSelectWrap.style.display = on ? '' : 'none';
        titleWrap.style.display = on ? 'none' : '';
        if (!toggle.checked) {
            const sel = projectSelectWrap.querySelector('select[name="project_id"]');
            if (sel) sel.value = '';
        }
    }
    
    if (toggle) {
        toggle.addEventListener('change', updateProject);
        updateProject();
    }
});
</script>
@endsection


