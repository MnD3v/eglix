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

.btn-primary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-primary i {
    color: #000000 !important;
}

.btn-primary:hover i {
    color: #000000 !important;
}

.btn-outline-secondary {
    background: #ffffff;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #000000;
    border: 1px solid #e2e8f0;
}

.btn-outline-secondary i {
    color: #000000 !important;
}

.btn-outline-secondary:hover i {
    color: #000000 !important;
}

.btn-secondary i {
    color: #000000 !important;
}

.btn-primary,
.btn-outline-secondary,
.btn-secondary {
    font-weight: 700 !important;
}

/* Styles pour les select dropdowns */
.form-select:focus {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

.form-select option:checked,
.form-select option:hover {
    background-color: #FFCC00 !important;
    color: #000000 !important;
}

/* Override Bootstrap select color */
select.form-select:focus,
select.form-select:active {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

/* Styles pour les options des dropdowns */
.form-select option:hover,
.form-select option:focus,
.form-select option:checked {
    background-color: #FFCC00 !important;
    color: #000000 !important;
}

/* Style global pour tous les select */
select {
    border-color: #e2e8f0 !important;
}

select:focus {
    border-color: #FFCC00 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25) !important;
}

/* Forcer toutes les icônes à être noires */
.btn-close {
    background-color: #000000;
}

input-group-text i {
    color: #000000 !important;
}

.text-muted i {
    color: #64748b !important;
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
    <!-- AppBar Nouvelle Dîme -->
    <div class="appbar tithes-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('tithes.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvelle Dîme</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('tithes.store') }}">
        @csrf
        
        <!-- Section Informations de la Dîme -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dîme</h2>
            <p class="section-subtitle">Détails sur le membre et le montant de la dîme</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Membre</label>
                    <input type="text" id="selectedMemberName" class="form-control @error('member_id') is-invalid @enderror" 
                           placeholder="Cliquez pour sélectionner un membre..." readonly required
                           data-bs-toggle="modal" data-bs-target="#memberSelectionModal" style="cursor: pointer;">
                    <input type="hidden" name="member_id" id="selectedMemberId" value="{{ old('member_id', request('member_id')) }}">
                    @error('member_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
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
            </div>
        </div>

        <!-- Section Méthode de Paiement -->
        <div class="form-section">
            <h2 class="section-title">Méthode de Paiement</h2>
            <p class="section-subtitle">Détails sur le mode de paiement utilisé</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Méthode de Paiement</label>
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
                    <input name="reference" value="{{ old('reference') }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Numéro de transaction ou référence">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur cette dîme</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Ajoutez des notes ou commentaires sur cette dîme...">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex gap-3 justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer la Dîme
            </button>
        </div>
    </form>

    <!-- Modal de sélection de membre -->
    <div class="modal fade" id="memberSelectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #FFCC00; color: #000000;">
                    <h5 class="modal-title">
                        <i class="bi bi-people me-2"></i>Choisir un membre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
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
// Fonction globale pour sélectionner un membre
function selectMember(memberId, memberName) {
    document.getElementById('selectedMemberId').value = memberId;
    document.getElementById('selectedMemberName').value = memberName;
    
    // Fermer le modal Bootstrap correctement
    const modal = document.getElementById('memberSelectionModal');
    const modalInstance = bootstrap.Modal.getInstance(modal);
    if (modalInstance) {
        modalInstance.hide();
    } else {
        // Si pas d'instance, créer une nouvelle et la fermer
        const newModal = new bootstrap.Modal(modal);
        newModal.hide();
    }
}

// Fonction de recherche
function searchMembers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const memberCards = document.querySelectorAll('.member-card');
    
    memberCards.forEach(function(card) {
        const memberName = card.getAttribute('data-name').toLowerCase();
        
        if (memberName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', searchMembers);
    }
    
    // Sélection de membre
    const memberCards = document.querySelectorAll('.member-card');
    memberCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const memberId = this.getAttribute('data-id');
            const memberName = this.getAttribute('data-name');
            selectMember(memberId, memberName);
        });
        
        // Effet hover
        card.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#FFF8DC';
            this.style.borderColor = '#FFCC00';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
            this.style.borderColor = '';
        });
    });
    
    // Gestion du mode de paiement
    const paymentSelect = document.querySelector('select[name="payment_method"]');
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            const method = this.value;
            const referenceField = document.getElementById('referenceField');
            
            if (method === 'mobile' || method === 'bank') {
                referenceField.style.display = 'block';
            } else {
                referenceField.style.display = 'none';
                referenceField.querySelector('input').value = '';
            }
        });
    }
});
</script>
@endsection