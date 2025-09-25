@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- AppBar Membres -->
    <div class="appbar members-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Membres</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-person-check appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">Gérez les membres de votre église</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <button onclick="generateAndCopyLink()" class="btn-add me-2">
                    <i class="bi bi-share"></i>
                    <span class="btn-text">Partager le lien</span>
                </button>
                <a href="{{ route('members.create') }}" class="btn-add">
                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Nouveau membre</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <!-- Statistiques membres -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-info p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">TOTAL</div>
                        <div class="kpi-value">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-success p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">ACTIFS</div>
                        <div class="kpi-value">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-check2-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-warning p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">INACTIFS</div>
                        <div class="kpi-value">{{ $stats['inactive'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-slash-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card accent-purple p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">ENFANTS (<18)</div>
                        <div class="kpi-value">{{ $stats['children'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-emoji-smile"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">HOMMES</div>
                        <div class="kpi-value">{{ $stats['male'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-male"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">FEMMES</div>
                        <div class="kpi-value">{{ $stats['female'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-female"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="kpi-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="kpi-label">AUTRES</div>
                        <div class="kpi-value">{{ $stats['other'] ?? 0 }}</div>
                    </div>
                    <div class="kpi-icon"><i class="bi bi-gender-ambiguous"></i></div>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Rechercher un membre (nom, email, téléphone)" name="q" value="{{ $search ?? '' }}">
            @if(!empty($search))
            <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Effacer</a>
            @endif
            <button class="btn btn" type="submit">Rechercher</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @forelse($members as $member)
            <div class="col">
                <div class="card card-soft h-100 position-relative">
                    <div class="card-body d-flex gap-3 card-link" data-href="{{ route('members.show', $member) }}" style="cursor: pointer;">
                        <div class="flex-shrink-0">
                            @if($member->photo_url || $member->profile_photo)
                                @php
                                    $photoUrl = $member->photo_url ?: asset('storage/' . $member->profile_photo);
                                @endphp
                                <img src="{{ $photoUrl }}" 
                                     alt="Photo de {{ $member->first_name }} {{ $member->last_name }}" 
                                     class="rounded-circle" 
                                     style="width:48px;height:48px;object-fit:cover;border:2px solid #E0F2FE;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#E0F2FE;color:#0EA5E9;font-weight:700;display:none;">{{ $initials }}</div>
                            @else
                                @php $initials = strtoupper(mb_substr($member->first_name ?? '',0,1).mb_substr($member->last_name ?? '',0,1)); @endphp
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#E0F2FE;color:#0EA5E9;font-weight:700;">{{ $initials }}</div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold text-dark">{{ $member->last_name }} {{ $member->first_name }}</div>
                                    <div class="small text-muted">{{ $member->email ?: '—' }}</div>
                                </div>
                                <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'secondary' }}">{{ $member->status }}</span>
                            </div>
                            <div class="mt-2 small text-muted"><i class="bi bi-telephone me-1"></i>{{ $member->phone ?: '—' }}</div>
                            
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between gap-2">
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('members.edit', $member) }}">Modifier</a>
                            <button class="btn btn-sm btn" data-bs-toggle="modal" data-bs-target="#addTitheModal-{{ $member->id }}">
                                <i class="bi bi-cash-coin me-1"></i>Ajouter dîme
                            </button>
                        </div>
                        <form action="{{ route('members.destroy', $member) }}" method="POST" data-confirm="Supprimer ce membre ?" data-confirm-ok="Supprimer">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </div>
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
                                        <input type="text" name="payment_method" class="form-control" placeholder="Espèces, Mobile Money…">
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
            <div class="col-12"><div class="text-center text-muted py-5">Aucun membre</div></div>
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


