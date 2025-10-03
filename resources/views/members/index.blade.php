@extends('layouts.app')

@section('content')
<style>
/* Styles simples et doux pour les lignes de membres */
.members-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.member-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.member-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.member-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.member-avatar-container {
    position: relative;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    order: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.member-photo {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #ffffff;
}


.member-avatar-fallback {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
    display: none !important;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #64748b;
    font-weight: 600;
    font-size: 16px;
    border: 2px solid #ffffff;
    align-items: center;
    justify-content: center;
}


.member-photo[style*="display: none"] + .member-avatar-fallback {
    display: flex !important;
}

.member-info {
    flex: 1;
    min-width: 0;
    order: 2;
    display: flex;
    flex-direction: column;
    gap: 4px;
    justify-content: center;
}

.member-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}


.member-phone {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}


.member-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    order: 3;
    min-height: 40px;
}

.member-actions .btn {
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    padding: 8px 16px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
    background-color: #ffffff !important;
    border: 1px solid #e2e8f0;
    color: #000000 !important;
}

.member-actions .btn i {
    color: #000000 !important;
}




.member-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background-color: #ffffff !important;
    border-color: #cbd5e1;
    color: #000000 !important;
}

.member-actions .btn:hover i {
    color: #000000 !important;
}

/* Styles pour le champ de recherche arrondi */
.search-group {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-icon {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 25px 0 0 25px;
    color: #000000 !important;
    font-size: 16px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-icon i {
    color: #000000 !important;
}

.search-input {
    border: 1px solid #e2e8f0;
    border-left: none;
    border-right: none;
    background-color: #ffffff;
    border-radius: 0;
    padding: 12px 16px;
    font-size: 14px;
}

.search-input:focus {
    border-color: #e2e8f0;
    box-shadow: none;
    background-color: #ffffff;
}

.search-btn {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: none;
    border-radius: 0 25px 25px 0;
    color: #000000;
    font-weight: 600;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-btn:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #000000;
}

.search-btn i {
    color: #000000 !important;
}

.search-btn:hover i {
    color: #000000 !important;
}

.btn-primary,
.btn-outline-secondary,
.search-btn,
.appbar-btn-white,
.appbar-btn-yellow {
    font-weight: 700 !important;
}

.member-row-body {
    display: flex;
    gap: 16px;
    align-items: center;
    cursor: pointer;
    flex: 1;
    transition: background-color 0.2s ease;
    min-height: 32px;
}

.member-row-body:hover {
    background: transparent;
}

/* Animation pour les lignes vides */
.member-row-empty {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
    font-size: 16px;
    background: #fafbfc;
    border: 1px dashed #e2e8f0;
    border-radius: 12px;
}

.member-row-empty i {
    font-size: 32px;
    margin-bottom: 12px;
    opacity: 0.6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .member-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        padding: 1rem;
    }
    
    .member-row-body {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.75rem;
    }
    
    .member-avatar-container {
        width: 40px;
        height: 40px;
    }
    
    .member-photo,
    .member-avatar-fallback {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .member-name {
        font-size: 15px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
    }
    
    .member-actions {
        flex-direction: row;
        gap: 6px;
    }
    
    .member-actions .btn {
        width: auto;
        height: auto;
        font-size: 12px;
        padding: 6px 12px;
        min-width: 100px;
        white-space: nowrap;
    }
    
    /* Forcer l'affichage du texte pour le bouton dîme sur mobile */
    .show-text-mobile .btn-label {
        display: inline !important;
    }
}
</style>
<div class="container py-4">
    <!-- AppBar Membres -->
    <div class="appbar members-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Membres</h1>
                </div>
            </div>
            <div class="appbar-right">
                <button onclick="generateAndCopyLink()" class="appbar-btn-white me-2">
                    <i class="bi bi-share"></i>
                    <span class="btn-text">Partager le lien</span>
                </button>
                <a href="{{ route('members.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Nouveau membre</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Indicateur de filtre actif -->
    @if($status || $gender || $age)
        <div class="alert alert-light mb-3">
            <i class="bi bi-funnel"></i>
            <strong>Filtre actif :</strong>
            @if($status)
                <span class="badge bg-secondary me-2">Statut: {{ ucfirst($status) }}</span>
            @endif
            @if($gender)
                <span class="badge bg-secondary me-2">Genre: {{ ucfirst($gender) }}</span>
            @endif
            @if($age === 'children')
                <span class="badge bg-secondary me-2">Âge: Enfants (<18 ans)</span>
            @endif
            <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                <i class="bi bi-x-circle"></i> Supprimer les filtres
            </a>
        </div>
    @endif

    <!-- Statistiques membres -->
    <div class="row g-2 g-lg-3 mb-4">
        <div class="col-6 col-lg-3">
            <a href="{{ route('members.index') }}" class="text-decoration-none">
                <div class="kpi-card stat-card">
                    <div class="kpi-header">
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title">Total membres</h3>
                        <p class="kpi-description" style="color: #64748b !important;">Nombre total de membres</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('members.index', ['status' => 'active']) }}" class="text-decoration-none">
                <div class="kpi-card stat-card">
                    <div class="kpi-header">
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-value">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title">Membres actifs</h3>
                        <p class="kpi-description" style="color: #64748b !important;">Membres en activité</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('members.index', ['age' => 'children']) }}" class="text-decoration-none">
                <div class="kpi-card stat-card">
                    <div class="kpi-header">
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-value">{{ $stats['children'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title">Enfants (<18 ans)</h3>
                        <p class="kpi-description" style="color: #64748b !important;">Membres mineurs</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('members.index', ['gender' => 'male']) }}" class="text-decoration-none">
                <div class="kpi-card stat-card">
                    <div class="kpi-header">
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-value">{{ $stats['male'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title">Hommes</h3>
                        <p class="kpi-description" style="color: #64748b !important;">Membres masculins</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('members.index', ['gender' => 'female']) }}" class="text-decoration-none">
                <div class="kpi-card stat-card">
                    <div class="kpi-header">
                    </div>
                    <div class="kpi-meta">
                        <div class="kpi-value">{{ $stats['female'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-info">
                        <h3 class="kpi-title">Femmes</h3>
                        <p class="kpi-description" style="color: #64748b !important;">Membres féminins</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Section Gestion des Invités -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="kpi-card stat-card" style="border-left: 4px solid #FFCC00;">
                <div class="kpi-header">
                </div>
                <div class="kpi-meta">
                    <div class="kpi-value" style="color: #FFCC00;">👥</div>
                </div>
                <div class="kpi-info">
                    <h3 class="kpi-title">Gestion des Invités</h3>
                    <p class="kpi-description" style="color: #64748b !important;">Visiteurs et nouvelles conversions</p>
                </div>
                <div class="kpi-actions" style="margin-left: auto;">
                    <a href="{{ route('guests.index') }}" class="btn btn-outline-secondary" style="background: #ffffff; color: #000000 !important; border: 1px solid #e2e8f0; font-weight: 700 !important;">
                        <i class="bi bi-eye me-1" style="color: #000000 !important;"></i>Voir les Invités
                    </a>
                    <a href="{{ route('guests.create') }}" class="btn btn-primary ms-2">
                        <i class="bi bi-person-plus me-1" style="color: #000000 !important;"></i>Nouvel Invité
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group search-group">
            <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control search-input" placeholder="Rechercher un membre (nom, email, téléphone)" name="q" value="{{ $search ?? '' }}">
            <button class="btn btn search-btn" type="submit">
                <i class="bi bi-search"></i>
                <span>Rechercher</span>
            </button>
        </div>
    </form>

    <div class="members-list">
        @forelse($members as $index => $member)
            <div class="member-row {{ $index > 0 ? 'member-row-separated' : '' }}">
                <div class="member-row-body card-link" data-href="{{ route('members.show', $member) }}">
                    <div class="member-avatar-container">
                        @php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
                        
                        @if($member->photo_url || $member->profile_photo)
                            @php
                                $photoUrl = $member->photo_url ?: asset('storage/' . $member->profile_photo);
                            @endphp
                            <img src="{{ $photoUrl }}" 
                                 alt="Photo de {{ $member->first_name }} {{ $member->last_name }}" 
                                 class="member-photo" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="member-avatar-fallback d-flex align-items-center justify-content-center">{{ $initials }}</div>
                        @else
                            <div class="member-avatar-fallback d-flex align-items-center justify-content-center" style="display: flex !important;">{{ $initials }}</div>
                        @endif
                    </div>
                    <div class="member-info">
                        <div class="member-name">{{ $member->last_name }} {{ $member->first_name }}</div>
                        <div class="member-phone">
                            <i class="bi bi-telephone"></i>
                            <span>{{ $member->phone ?: '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="member-actions">
                    <button class="btn btn-light show-text-mobile" data-bs-toggle="modal" data-bs-target="#addTitheModal-{{ $member->id }}" title="Ajouter une dîme">
                        <i class="bi bi-cash-coin me-2"></i>Ajouter dîme
                    </button>
                </div>
            </div>

            <!-- Modal Ajouter dîme -->
            <div class="modal fade" id="addTitheModal-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter une dîme — {{ $member->last_name }} {{ $member->first_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('tithes.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="member_id" value="{{ $member->id }}">
                            <input type="hidden" name="redirect" value="{{ route('members.index', request()->query()) }}">
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="paid_at" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Montant</label>
                                        <input type="number" name="amount" class="form-control" step="0.01" min="0" placeholder="0,00" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Moyen de paiement</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="">Sélectionner...</option>
                                            <option value="Espèces">Espèces</option>
                                            <option value="Mobile Money">Mobile Money</option>
                                            <option value="Banque">Banque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Référence</label>
                                        <input type="text" name="reference" class="form-control" placeholder="#REF">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Optionnel"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="member-row-empty">
                <i class="bi bi-people"></i>
                <div>Aucun membre trouvé</div>
                <small class="text-muted mt-2">Commencez par ajouter votre premier membre</small>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $members->links() }}
    </div>
</div>

<script>
function copyToClipboard(text, buttonElement = null) {
    // Méthode moderne avec fallback
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(function() {
            showCopySuccess(buttonElement);
        }).catch(function(err) {
            console.error('Erreur avec navigator.clipboard: ', err);
            fallbackCopyTextToClipboard(text, buttonElement);
        });
    } else {
        // Fallback pour les navigateurs plus anciens ou contextes non sécurisés
        fallbackCopyTextToClipboard(text, buttonElement);
    }
}

function fallbackCopyTextToClipboard(text, buttonElement) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Éviter le défilement vers l'élément
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(buttonElement);
        } else {
            throw new Error('execCommand failed');
        }
    } catch (err) {
        console.error('Erreur avec execCommand: ', err);
        alert('Erreur lors de la copie du lien. Veuillez copier manuellement: ' + text);
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess(buttonElement) {
    if (buttonElement) {
        const originalHTML = buttonElement.innerHTML;
        buttonElement.innerHTML = '<i class="bi bi-check"></i> Copié';
        buttonElement.classList.remove('btn-outline-primary');
        buttonElement.classList.add('btn-success');
        
        setTimeout(() => {
            buttonElement.innerHTML = originalHTML;
            buttonElement.classList.remove('btn-success');
            buttonElement.classList.add('btn-outline-primary');
        }, 2000);
    }
}
</script>
@endsection

<script>
// Fonction pour générer et copier le lien directement
function generateAndCopyLink() {
    // Afficher un indicateur de chargement
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i> <span class="btn-text">Génération...</span>';
    button.disabled = true;
    
    // Faire une requête AJAX pour générer le lien
    fetch('{{ route("members.generate-link") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Copier le lien dans le presse-papier
            copyToClipboard(data.registration_link, button);
            
            // Afficher le message de succès
            showSuccessMessage(data.registration_link);
            
            // Restaurer le bouton
            button.innerHTML = originalHTML;
            button.disabled = false;
        } else {
            throw new Error(data.message || 'Erreur lors de la génération du lien');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la génération du lien: ' + error.message);
        
        // Restaurer le bouton
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}

// Fonction pour afficher le message de succès
function showSuccessMessage(link) {
    // Supprimer l'ancien message s'il existe
    const existingAlert = document.querySelector('.alert-success');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Créer le nouveau message
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success';
    alertDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>✅ Lien copié dans le presse-papier !</strong><br>
                <small class="text-muted">Le lien d'inscription est maintenant prêt à être partagé</small>
            </div>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" value="${link}" readonly style="width: 300px;" id="registration-link-input">
                <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('${link}', this)">
                    <i class="bi bi-copy"></i> Copier à nouveau
                </button>
            </div>
        </div>
    `;
    
    // Insérer le message après les alertes existantes
    const container = document.querySelector('.container.py-4');
    const firstChild = container.children[1]; // Après l'appbar
    container.insertBefore(alertDiv, firstChild);
    
    // Auto-supprimer après 10 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 10000);
}
</script>

