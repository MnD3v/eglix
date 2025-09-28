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
    <!-- AppBar Modifier Don -->
    <div class="appbar donations-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('donations.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier Don</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('donations.update', $donation) }}">
        @csrf
        @method('PUT')
        
        <!-- Section Informations du Don -->
        <div class="form-section">
            <h2 class="section-title">Informations du Don</h2>
            <p class="section-subtitle">Détails sur le don à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="externalDonorToggle" name="external_donor" value="1" @checked(old('external_donor', $donation->member_id ? 0 : 1))>
                        <label class="form-check-label fw-semibold" for="externalDonorToggle">Donateur externe</label>
                    </div>
                    <div id="memberSelectWrap">
                        <label class="form-label">Membre</label>
                        <select name="member_id" class="form-select select2-members @error('member_id') is-invalid @enderror">
                            <option value="">Rechercher un membre...</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}" @selected(old('member_id', $donation->member_id)==$m->id)>{{ $m->last_name }} {{ $m->first_name }}</option>
                            @endforeach
                        </select>
                        @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div id="externalDonorWrap" style="display:none;">
                        <label class="form-label">Nom du donateur externe</label>
                        <input name="donor_name" value="{{ old('donor_name', $donation->donor_name) }}" class="form-control @error('donor_name') is-invalid @enderror" placeholder="Ex: Jean Dupont, Entreprise ABC...">
                        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="hasProjectToggle" name="has_project" value="1" @checked(old('has_project', $donation->project_id ? 1 : 0))>
                        <label class="form-check-label fw-semibold" for="hasProjectToggle">Lier à un projet</label>
                    </div>
                    <div id="projectSelectWrap" style="display:none;">
                        <label class="form-label">Projet</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id', $donation->project_id)==$p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div id="titleWrap" style="display:none;">
                        <label class="form-label">Titre du don</label>
                        <input name="title" value="{{ old('title', $donation->project_id ? '' : $donation->title) }}" class="form-control @error('title') is-invalid @enderror" placeholder="Ex: Don pour l'église, Offrande spéciale...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de Réception</label>
                    <input type="date" name="received_at" value="{{ old('received_at', optional($donation->received_at)->format('Y-m-d')) }}" class="form-control @error('received_at') is-invalid @enderror" required>
                    @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Type de Don -->
        <div class="form-section">
            <h2 class="section-title">Type de Don</h2>
            <p class="section-subtitle">Sélectionnez le type de don</p>
            
            <div class="row g-3">
                <!-- Toggle pour le type de don -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Type de don *</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="donation_type" id="donation_type_money" value="money" {{ old('donation_type', $donation->donation_type ?? 'money') == 'money' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary donation-type-btn" for="donation_type_money">
                            <i class="bi bi-cash-coin me-2"></i>Argent
                        </label>
                        
                        <input type="radio" class="btn-check" name="donation_type" id="donation_type_physical" value="physical" {{ old('donation_type', $donation->donation_type) == 'physical' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary donation-type-btn" for="donation_type_physical">
                            <i class="bi bi-box me-2"></i>Objet physique
                        </label>
                    </div>
                    @error('donation_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <!-- Champs pour l'argent -->
                <div class="col-md-6" id="amount-field">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $donation->amount) }}" class="form-control @error('amount') is-invalid @enderror" placeholder="Ex: 10000">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" id="payment-method-field">
                    <label class="form-label">Méthode de paiement</label>
                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">—</option>
                        <option value="cash" @selected(old('payment_method', $donation->payment_method)==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method', $donation->payment_method)==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method', $donation->payment_method)==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Champs pour l'objet physique -->
                <div class="col-md-6" id="physical-item-field" style="display: none;">
                    <label class="form-label">Objet donné</label>
                    <input type="text" name="physical_item" value="{{ old('physical_item', $donation->physical_item) }}" class="form-control @error('physical_item') is-invalid @enderror" placeholder="Ex: Livres, vêtements, nourriture...">
                    @error('physical_item')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12" id="physical-description-field" style="display: none;">
                    <label class="form-label">Description de l'objet</label>
                    <textarea name="physical_description" class="form-control @error('physical_description') is-invalid @enderror" rows="3" placeholder="Décrivez l'objet donné...">{{ old('physical_description', $donation->physical_description) }}</textarea>
                    @error('physical_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur le don</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes complémentaires sur le don...">{{ old('notes', $donation->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.donation-type-btn {
    background-color: white !important;
    border: 2px solid #e2e8f0 !important;
    color: #64748b !important;
    transition: all 0.3s ease;
    border-radius: 12px !important;
    font-weight: 600;
}

.donation-type-btn:hover {
    background-color: #f8fafc !important;
    border-color: #FFCC00 !important;
    color: #1e293b !important;
}

.btn-check:checked + .donation-type-btn {
    background-color: #FFCC00 !important;
    border-color: #FFCC00 !important;
    color: #000000 !important;
}

.btn-check:checked + .donation-type-btn:hover {
    background-color: #e6b800 !important;
    border-color: #e6b800 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du toggle donateur externe
    const externalDonorToggle = document.getElementById('externalDonorToggle');
    const memberSelectWrap = document.getElementById('memberSelectWrap');
    const externalDonorWrap = document.getElementById('externalDonorWrap');
    
    function toggleDonorFields() {
        if (externalDonorToggle.checked) {
            memberSelectWrap.style.display = 'none';
            externalDonorWrap.style.display = 'block';
        } else {
            memberSelectWrap.style.display = 'block';
            externalDonorWrap.style.display = 'none';
        }
    }
    
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
    
    // Gestion du type de don
    const donationTypeMoney = document.getElementById('donation_type_money');
    const donationTypePhysical = document.getElementById('donation_type_physical');
    const amountField = document.getElementById('amount-field');
    const paymentMethodField = document.getElementById('payment-method-field');
    const physicalItemField = document.getElementById('physical-item-field');
    const physicalDescriptionField = document.getElementById('physical-description-field');
    
    function toggleDonationTypeFields() {
        if (donationTypeMoney.checked) {
            amountField.style.display = 'block';
            paymentMethodField.style.display = 'block';
            physicalItemField.style.display = 'none';
            physicalDescriptionField.style.display = 'none';
        } else if (donationTypePhysical.checked) {
            amountField.style.display = 'none';
            paymentMethodField.style.display = 'none';
            physicalItemField.style.display = 'block';
            physicalDescriptionField.style.display = 'block';
        }
    }
    
    // Initialisation
    toggleDonorFields();
    toggleProjectFields();
    toggleDonationTypeFields();
    
    // Event listeners
    externalDonorToggle.addEventListener('change', toggleDonorFields);
    hasProjectToggle.addEventListener('change', toggleProjectFields);
    donationTypeMoney.addEventListener('change', toggleDonationTypeFields);
    donationTypePhysical.addEventListener('change', toggleDonationTypeFields);
    
    // Initialisation explicite de Select2 pour les membres
    $(document).ready(function() {
        $('.select2-members').select2({
            placeholder: "Rechercher un membre...",
            allowClear: false,
            width: '100%',
            minimumInputLength: 0,
            matcher: function(params, data) {
                // Si aucun terme de recherche, afficher tous les résultats
                if ($.trim(params.term) === '') {
                    return data;
                }
                
                // Recherche insensible à la casse
                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }
                
                return null;
            },
            language: {
                noResults: function() {
                    return "Aucun membre trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            }
        });
    });
});
</script>
@endsection


