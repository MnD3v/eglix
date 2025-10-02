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

/* Styles pour les boutons de type de don */
.donation-type-btn {
    background-color: white !important;
    border: 2px solid #e2e8f0 !important;
    color: #64748b !important;
    transition: all 0.3s ease;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.donation-type-btn:hover {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}

.btn-check:checked + .donation-type-btn {
    background-color: #FFCC00 !important;
    border-color: #FFCC00 !important;
    color: #1e293b !important;
}

.btn-check:checked + .donation-type-btn:hover {
    background-color: #e6b800 !important;
    border-color: #e6b800 !important;
}

/* Style pour les items de membre */
.member-item {
    cursor: pointer;
    transition: all 0.2s ease;
}

.member-item:hover {
    background-color: #f8fafc;
    border-color: #FFCC00 !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Nouveau Don -->
    <div class="appbar donations-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('donations.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouveau Don</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('donations.store') }}">
        @csrf
        
        <!-- Section Donateur -->
        <div class="form-section">
            <h2 class="section-title">Donateur</h2>
            <p class="section-subtitle">Informations sur la personne ou l'entité qui fait le don</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="externalDonorToggle" name="external_donor" value="1" @checked(old('external_donor'))>
                        <label class="form-check-label" for="externalDonorToggle">
                            <i class="bi bi-person-plus me-2"></i>Donateur externe
                        </label>
                    </div>
                    <div id="memberSelectWrap">
                        <label class="form-label">Membre</label>
                        <input type="text" id="selectedMemberName" class="form-control @error('member_id') is-invalid @enderror" 
                               placeholder="Cliquez pour sélectionner un membre..." readonly
                               data-bs-toggle="modal" data-bs-target="#memberSelectionModal" style="cursor: pointer;">
                        <input type="hidden" name="member_id" id="selectedMemberId" value="{{ old('member_id') }}">
                        @error('member_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div id="externalDonorWrap" style="display:none;">
                        <label class="form-label">Nom du Donateur Externe</label>
                        <input name="donor_name" value="{{ old('donor_name') }}" class="form-control @error('donor_name') is-invalid @enderror" placeholder="Ex: Jean Dupont, Entreprise ABC...">
                        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
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
                        <label class="form-label">Titre du Don</label>
                        <input name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Ex: Don pour l'église, Offrande spéciale...">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Informations du Don -->
        <div class="form-section">
            <h2 class="section-title">Informations du Don</h2>
            <p class="section-subtitle">Détails sur le type et la valeur du don</p>
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date de Réception</label>
                    <input type="date" name="received_at" value="{{ old('received_at', now()->format('Y-m-d')) }}" class="form-control @error('received_at') is-invalid @enderror" required>
                    @error('received_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <!-- Type de don -->
                <div class="col-12">
                    <label class="form-label">Type de Don</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="donation_type" id="donation_type_money" value="money" {{ old('donation_type', 'money') == 'money' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary donation-type-btn" for="donation_type_money">
                            <i class="bi bi-cash-coin me-2"></i>Argent
                        </label>
                        
                        <input type="radio" class="btn-check" name="donation_type" id="donation_type_physical" value="physical" {{ old('donation_type') == 'physical' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary donation-type-btn" for="donation_type_physical">
                            <i class="bi bi-box me-2"></i>Objet Physique
                        </label>
                    </div>
                    @error('donation_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <!-- Champs pour l'argent -->
                <div class="col-md-3" id="amount-field">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3" id="payment-method-field">
                    <label class="form-label">Méthode de Paiement</label>
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
                    <label class="form-label">Objet Donné</label>
                    <input type="text" name="physical_item" value="{{ old('physical_item') }}" class="form-control @error('physical_item') is-invalid @enderror" placeholder="Ex: Livres, vêtements, nourriture...">
                    @error('physical_item')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12" id="physical-description-field" style="display: none;">
                    <label class="form-label">Description de l'Objet</label>
                    <textarea name="physical_description" class="form-control @error('physical_description') is-invalid @enderror" rows="3" placeholder="Décrivez l'objet donné...">{{ old('physical_description') }}</textarea>
                    @error('physical_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4" id="reference-field">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Numéro de transaction ou référence">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur ce don</p>
            
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Ajoutez des notes ou commentaires sur ce don...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-3 justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer le Don
            </button>
        </div>
    </form>

    <!-- Modal de sélection de membre -->
    <div class="modal fade" id="memberSelectionModal" tabindex="-1" aria-labelledby="memberSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #FFCC00; color: #000000;">
                    <h5 class="modal-title">
                        <i class="bi bi-people me-2"></i>Choisir un membre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Champ de recherche -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Rechercher un membre :</label>
                        <input type="text" id="searchInput" class="form-control form-control-lg" 
                               placeholder="Tapez le nom du membre...">
                    </div>
                    
                    <!-- Liste des membres -->
                    <div id="memberList" style="max-height: 350px; overflow-y: auto;">
                        @foreach($members as $member)
                        <div class="member-card p-3 mb-2 border rounded-3" 
                             data-id="{{ $member->id }}" 
                             data-name="{{ $member->last_name }} {{ $member->first_name }}"
                             style="cursor: pointer; transition: all 0.3s ease;">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; font-weight: bold; background-color: #FFCC00; color: #000000;">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $member->last_name }} {{ $member->first_name }}</h6>
                                    <div class="text-muted small">
                                        @if($member->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $member->phone }}
                                        @endif
                                        @if($member->email)
                                            <i class="bi bi-envelope me-1 ms-2"></i>{{ $member->email }}
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // =========================
    // Gestion du type de don
    // =========================
    const moneyRadio = document.getElementById('donation_type_money');
    const physicalRadio = document.getElementById('donation_type_physical');
    const amountField = document.getElementById('amount-field');
    const paymentMethodField = document.getElementById('payment-method-field');
    const physicalItemField = document.getElementById('physical-item-field');
    const physicalDescriptionField = document.getElementById('physical-description-field');
    const referenceField = document.getElementById('reference-field');

    function toggleDonationTypeFields() {
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
            referenceField.style.display = 'none';
        }
    }

    function updateReferenceField() {
        const paymentMethod = document.querySelector('select[name="payment_method"]');
        
        if (paymentMethod && moneyRadio.checked) {
            const showReference = paymentMethod.value === 'mobile' || paymentMethod.value === 'bank';
            
            if (showReference) {
                referenceField.style.display = 'block';
            } else {
                referenceField.style.display = 'none';
                const referenceInput = referenceField.querySelector('input[name="reference"]');
                if (referenceInput) referenceInput.value = '';
            }
        } else {
            referenceField.style.display = 'none';
        }
    }

    if (moneyRadio) moneyRadio.addEventListener('change', toggleDonationTypeFields);
    if (physicalRadio) physicalRadio.addEventListener('change', toggleDonationTypeFields);
    
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', updateReferenceField);
    }
    
    // Initialisation
    toggleDonationTypeFields();
    setTimeout(function() {
        updateReferenceField();
    }, 100);
    
    // =========================
    // Gestion du donateur externe
    // =========================
    const externalDonorToggle = document.getElementById('externalDonorToggle');
    const memberSelectWrap = document.getElementById('memberSelectWrap');
    const externalDonorWrap = document.getElementById('externalDonorWrap');
    
    function updateExternalDonor() {
        if (!externalDonorToggle || !memberSelectWrap || !externalDonorWrap) return;
        const isExternal = externalDonorToggle.checked;
        
        memberSelectWrap.style.display = isExternal ? 'none' : 'block';
        externalDonorWrap.style.display = isExternal ? 'block' : 'none';
        
        if (isExternal) {
            document.getElementById('selectedMemberId').value = '';
            document.getElementById('selectedMemberName').value = '';
        } else {
            const donorInput = externalDonorWrap.querySelector('input[name="donor_name"]');
            if (donorInput) donorInput.value = '';
        }
    }
    
    if (externalDonorToggle) {
        externalDonorToggle.addEventListener('change', updateExternalDonor);
        updateExternalDonor();
    }
    
    // =========================
    // Gestion du projet
    // =========================
    const hasProjectToggle = document.getElementById('hasProjectToggle');
    const projectSelectWrap = document.getElementById('projectSelectWrap');
    const titleWrap = document.getElementById('titleWrap');
    
    function updateProjectFields() {
        if (!hasProjectToggle || !projectSelectWrap || !titleWrap) return;
        const hasProject = hasProjectToggle.checked;
        
        projectSelectWrap.style.display = hasProject ? 'block' : 'none';
        titleWrap.style.display = hasProject ? 'none' : 'block';
        
        if (!hasProject) {
            const projectSelect = projectSelectWrap.querySelector('select[name="project_id"]');
            if (projectSelect) projectSelect.value = '';
        }
    }
    
    if (hasProjectToggle) {
        hasProjectToggle.addEventListener('change', updateProjectFields);
        updateProjectFields();
    }
});

// =========================
// Fonctionnalité de sélection de membre avec jQuery
// =========================
$(document).ready(function() {
    // Initialiser le nom du membre sélectionné si déjà défini
    const selectedMemberId = $('#selectedMemberId').val();
    if (selectedMemberId) {
        const selectedMember = $('.member-item[data-member-id="' + selectedMemberId + '"]');
        if (selectedMember.length) {
            $('#selectedMemberName').val(selectedMember.data('member-name'));
        }
    }

    // Fonction globale pour sélectionner un membre
    window.selectMember = function(memberId, memberName) {
        document.getElementById('selectedMemberId').value = memberId;
        document.getElementById('selectedMemberName').value = memberName;
        
        // Fermer le modal avec Bootstrap
        const modal = document.getElementById('memberSelectionModal');
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    };

    // Fonction de recherche
    function searchMembers() {
        const searchInput = document.getElementById('searchInput');
        const memberCards = document.querySelectorAll('.member-card');
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        memberCards.forEach(card => {
            const memberName = card.getAttribute('data-name').toLowerCase();
            if (memberName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Attacher les événements
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const memberCards = document.querySelectorAll('.member-card');
        
        // Recherche en temps réel
        searchInput.addEventListener('input', searchMembers);
        
        // Sélection de membre
        memberCards.forEach(card => {
            card.addEventListener('click', function() {
                const memberId = this.getAttribute('data-id');
                const memberName = this.getAttribute('data-name');
                selectMember(memberId, memberName);
            });
            
            // Effets hover
            card.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#FFF8DC';
                this.style.borderColor = '#FFCC00';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.borderColor = '';
            });
        });
    });
});
</script>
@endsection