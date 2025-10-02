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
input-group-text i {
    color: #000000 !important;
}

.text-muted i {
    color: #64748b !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .form-section {
        padding: 1.5rem;
    }
}
</style>

<div class="container-fluid px-4 py-4">
    <!-- AppBar Modifier Dîme -->
    <div class="appbar tithes-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('tithes.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier Dîme</h1>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('tithes.update', $tithe) }}">
        @csrf
        @method('PUT')
        
        <!-- Section Informations de la Dîme -->
        <div class="form-section">
            <h2 class="section-title">Informations de la Dîme</h2>
            <p class="section-subtitle">Détails sur la dîme à modifier</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Membre</label>
                    <div class="input-group">
                        <input type="text" id="selectedMemberName" class="form-control @error('member_id') is-invalid @enderror" 
                               placeholder="Cliquez pour sélectionner un membre..." readonly required
                               data-bs-toggle="modal" data-bs-target="#memberSelectionModal" style="cursor: pointer;">
                        <input type="hidden" name="member_id" id="selectedMemberId" value="{{ old('member_id', $tithe->member_id) }}">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#memberSelectionModal">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date de Paiement</label>
                    <input type="date" name="paid_at" value="{{ old('paid_at', optional($tithe->paid_at)->format('Y-m-d')) }}" class="form-control @error('paid_at') is-invalid @enderror" required>
                    @error('paid_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Montant (FCFA)</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $tithe->amount) }}" class="form-control @error('amount') is-invalid @enderror" required placeholder="Ex: 5000">
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
                        <option value="cash" @selected(old('payment_method', $tithe->payment_method)==='cash')>Espèces</option>
                        <option value="mobile" @selected(old('payment_method', $tithe->payment_method)==='mobile')>Mobile money</option>
                        <option value="bank" @selected(old('payment_method', $tithe->payment_method)==='bank')>Banque</option>
                    </select>
                    @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6" id="referenceField" style="display:none;">
                    <label class="form-label">Référence</label>
                    <input name="reference" value="{{ old('reference', $tithe->reference) }}" class="form-control @error('reference') is-invalid @enderror" placeholder="Ex: MTN-123456789">
                    @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Section Notes -->
        <div class="form-section">
            <h2 class="section-title">Notes</h2>
            <p class="section-subtitle">Informations complémentaires sur la dîme</p>
            
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes complémentaires sur la dîme...">{{ old('notes', $tithe->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-section">
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('tithes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2" style="color: #000000;"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </form>

    <!-- Modal de sélection de membre -->
    <div class="modal fade" id="memberSelectionModal" tabindex="-1" aria-labelledby="memberSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberSelectionModalLabel">
                        <i class="bi bi-people me-2"></i>Sélectionner un membre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Champ de recherche -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="memberSearchInput" class="form-control" 
                                   placeholder="Rechercher par nom, prénom ou numéro de téléphone...">
                        </div>
                    </div>
                    
                    <!-- Liste des membres -->
                    <div class="members-list" style="max-height: 400px; overflow-y: auto;">
                        @foreach($members as $member)
                        <div class="member-item border rounded p-3 mb-2 cursor-pointer" 
                             data-member-id="{{ $member->id }}" 
                             data-member-name="{{ $member->last_name }} {{ $member->first_name }}"
                             style="cursor: pointer; transition: all 0.2s ease;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $member->last_name }} {{ $member->first_name }}</h6>
                                    <small class="text-muted">
                                        @if($member->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $member->phone }}
                                        @endif
                                        @if($member->email)
                                            <i class="bi bi-envelope me-1 ms-2"></i>{{ $member->email }}
                                        @endif
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        @if($member->birth_date)
                                            <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($member->birth_date)->age }} ans
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Initial check
    toggleReferenceField();
    
    // Listen for changes
    paymentMethodSelect.addEventListener('change', toggleReferenceField);
    
    // Fonctionnalité de sélection de membre
    $(document).ready(function() {
        // Initialiser le nom du membre sélectionné si déjà défini
        const selectedMemberId = $('#selectedMemberId').val();
        if (selectedMemberId) {
            const selectedMember = $('.member-item[data-member-id="' + selectedMemberId + '"]');
            if (selectedMember.length) {
                $('#selectedMemberName').val(selectedMember.data('member-name'));
            }
        }

        // Gestion de la sélection d'un membre
        $(document).on('click', '.member-item', function() {
            const memberId = $(this).data('member-id');
            const memberName = $(this).data('member-name');
            
            $('#selectedMemberId').val(memberId);
            $('#selectedMemberName').val(memberName);
            
            // Fermer le modal
            $('#memberSelectionModal').modal('hide');
        });

        // Fonctionnalité de recherche
        $(document).on('input', '#memberSearchInput', function() {
            const searchTerm = $(this).val().toLowerCase().trim();
            console.log('Recherche:', searchTerm); // Debug
            
            $('.member-item').each(function() {
                const memberName = $(this).data('member-name').toLowerCase();
                const memberPhone = $(this).find('.text-muted').text().toLowerCase();
                const memberEmail = $(this).find('.text-muted').text().toLowerCase();
                
                console.log('Membre:', memberName, 'Phone:', memberPhone); // Debug
                
                if (searchTerm === '' || 
                    memberName.includes(searchTerm) || 
                    memberPhone.includes(searchTerm) ||
                    memberEmail.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Effet hover sur les éléments de membre
        $(document).on('mouseenter', '.member-item', function() {
            $(this).addClass('bg-light border-primary');
        });
        
        $(document).on('mouseleave', '.member-item', function() {
            $(this).removeClass('bg-light border-primary');
        });
    });
});
</script>
@endsection


