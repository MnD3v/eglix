<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Membre - {{ $member->last_name }} {{ $member->first_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2d3748;
            background: #ffffff;
            font-weight: 400;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* En-tête avec logo et informations église */
        .header {
            border-bottom: 3px solid #FFCC00;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .logo-section {
            margin-bottom: 20px;
        }
        
        .logo {
            max-height: 60px;
            width: auto;
            margin-bottom: 15px;
        }
        
        .church-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        
        .generation-info {
            font-size: 9px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }
        
        /* Section membre */
        .member-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #FFCC00;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
        }
        
        .section-icon {
            margin-right: 8px;
            font-size: 18px;
        }
        
        /* Grille d'informations */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }
        
        .info-label {
            font-size: 9px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
        }
        
        .info-value {
            font-size: 11px;
            color: #1a202c;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .info-value.empty {
            color: #a0aec0;
            font-style: italic;
        }
        
        /* Section statistiques */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
            letter-spacing: 0.5px;
        }
        
        .stat-label {
            font-size: 9px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 500;
        }
        
        /* Section historique des dîmes */
        .tithes-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .tithes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .tithes-table th {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
        }
        
        .tithes-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.3;
        }
        
        .tithes-table tr:nth-child(even) {
            background: #f7fafc;
        }
        
        .amount {
            font-weight: 700;
            color: #2d3748;
            text-align: right;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
            letter-spacing: 0.3px;
        }
        
        /* Section remarques */
        .remarks-section {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .remark-item {
            background: #ffffff;
            border: 1px solid #fed7d7;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
        }
        
        .remark-text {
            font-size: 10px;
            margin-bottom: 8px;
            line-height: 1.6;
            font-weight: 400;
        }
        
        .remark-meta {
            font-size: 8px;
            color: #718096;
            border-top: 1px solid #fed7d7;
            padding-top: 8px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        /* Pied de page */
        .footer {
            border-top: 2px solid #FFCC00;
            padding-top: 15px;
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #718096;
            font-weight: 400;
            line-height: 1.4;
        }
        
        .footer-logo {
            margin-bottom: 10px;
        }
        
        /* Styles pour les colonnes complètes */
        .full-width {
            grid-column: 1 / -1;
        }
        
        /* Styles pour les éléments vides */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #a0aec0;
            font-style: italic;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.5;
        }
        
        /* Améliorations typographiques */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .footer-logo {
            font-weight: 600;
            font-size: 10px;
            margin-bottom: 8px;
        }
        
        /* Espacement des lettres pour les éléments importants */
        .church-name,
        .section-title,
        .stat-value {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Amélioration de la lisibilité */
        .info-value.empty {
            font-size: 10px;
            font-weight: 400;
        }
        
        /* Responsive pour PDF */
        @media print {
            .container {
                padding: 10px;
            }
            
            .member-section,
            .stats-section,
            .tithes-section,
            .remarks-section {
                break-inside: avoid;
                margin-bottom: 15px;
            }
        }
        
        /* Couleurs spécifiques */
        .text-primary { color: #FFCC00; }
        .text-success { color: #38a169; }
        .text-warning { color: #d69e2e; }
        .text-danger { color: #e53e3e; }
        
        /* Badges de statut */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-family: 'Plus Jakarta Sans', 'DejaVu Sans', Arial, sans-serif;
        }
        
        .status-active {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status-inactive {
            background: #fed7d7;
            color: #742a2a;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête du document -->
        <div class="header">
            <div class="logo-section">
                <div style="font-size: 28px; font-weight: 800; color: #FFCC00; margin-bottom: 15px; font-family: 'Plus Jakarta Sans', sans-serif;">
                    EGLIX
                </div>
            </div>
            <div class="church-name">{{ $church->name ?? 'Église' }}</div>
            <div class="document-title">📋 Dossier du Membre</div>
            <div class="generation-info">
                Généré le {{ $generated_at }} par {{ $generated_by }}
            </div>
        </div>

        <!-- Informations personnelles du membre -->
        <div class="member-section">
            <div class="section-title">
                <span class="section-icon">👤</span>
                Informations Personnelles
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value">{{ $member->last_name }} {{ $member->first_name }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Statut</div>
                    <div class="info-value">
                        <span class="status-badge {{ $member->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $member->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value {{ !$member->email ? 'empty' : '' }}">
                        {{ $member->email ?: 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value {{ !$member->phone ? 'empty' : '' }}">
                        {{ $member->phone ?: 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Genre</div>
                    <div class="info-value {{ !$member->gender ? 'empty' : '' }}">
                        {{ $member->gender ? ucfirst($member->gender) : 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Statut marital</div>
                    <div class="info-value {{ !$member->marital_status ? 'empty' : '' }}">
                        {{ $member->marital_status ? ucfirst($member->marital_status) : 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date de naissance</div>
                    <div class="info-value {{ !$member->birth_date ? 'empty' : '' }}">
                        {{ $member->birth_date ? $member->birth_date->format('d/m/Y') : 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date de baptême</div>
                    <div class="info-value {{ !$member->baptized_at ? 'empty' : '' }}">
                        {{ $member->baptized_at ? $member->baptized_at->format('d/m/Y') : 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Responsable du baptême</div>
                    <div class="info-value {{ !$member->baptism_responsible ? 'empty' : '' }}">
                        {{ $member->baptism_responsible ?: 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date d'adhésion</div>
                    <div class="info-value {{ !$member->joined_at ? 'empty' : '' }}">
                        {{ $member->joined_at ? $member->joined_at->format('d/m/Y') : 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Fonction</div>
                    <div class="info-value {{ !$member->function ? 'empty' : '' }}">
                        {{ $member->function ?: 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Domaine d'activité</div>
                    <div class="info-value {{ !$member->activity_domain ? 'empty' : '' }}">
                        {{ $member->activity_domain ?: 'Non renseigné' }}
                    </div>
                </div>
                
                <div class="info-item full-width">
                    <div class="info-label">Adresse</div>
                    <div class="info-value {{ !$member->address ? 'empty' : '' }}">
                        {{ $member->address ?: 'Non renseigné' }}
                    </div>
                </div>
                
                @if($member->notes)
                <div class="info-item full-width">
                    <div class="info-label">Notes</div>
                    <div class="info-value">{{ $member->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistiques financières -->
        <div class="stats-section">
            <div class="section-title" style="color: white; border-bottom-color: rgba(255,255,255,0.3);">
                <span class="section-icon">📊</span>
                Statistiques Financières
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($stats['total_tithes'], 0, ',', ' ') }} FCFA</div>
                    <div class="stat-label">Total des dîmes</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['tithes_count'] }}</div>
                    <div class="stat-label">Nombre de dîmes</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">{{ number_format($stats['this_year_tithes'], 0, ',', ' ') }} FCFA</div>
                    <div class="stat-label">Dîmes cette année</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value">{{ $stats['last_tithe_date'] ?: 'Aucune' }}</div>
                    <div class="stat-label">Dernière dîme</div>
                </div>
            </div>
        </div>

        <!-- Historique des dîmes -->
        <div class="tithes-section">
            <div class="section-title">
                <span class="section-icon">💰</span>
                Historique des Dîmes (10 dernières)
            </div>
            
            @if($member->tithes->count() > 0)
                <table class="tithes-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Méthode de paiement</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($member->tithes->take(10) as $tithe)
                        <tr>
                            <td>{{ $tithe->paid_at ? $tithe->paid_at->format('d/m/Y') : 'N/A' }}</td>
                            <td class="amount">{{ number_format($tithe->amount, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $tithe->payment_method ?: 'Non spécifié' }}</td>
                            <td>{{ $tithe->reference ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div>💰</div>
                    <div>Aucune dîme enregistrée pour ce membre</div>
                </div>
            @endif
        </div>

        <!-- Remarques disciplinaires -->
        @if($member->getFormattedRemarks() && count($member->getFormattedRemarks()) > 0)
        <div class="remarks-section">
            <div class="section-title" style="color: #742a2a;">
                <span class="section-icon">📝</span>
                Remarques Disciplinaires
            </div>
            
            @foreach($member->getFormattedRemarks() as $remark)
            <div class="remark-item">
                <div class="remark-text">{{ $remark['remark'] }}</div>
                <div class="remark-meta">
                    📅 {{ $remark['added_at'] }} • 👤 {{ $remark['added_by'] }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Pied de page -->
        <div class="footer">
            <div class="footer-logo">
                <strong>{{ $church->name ?? 'Église' }}</strong>
            </div>
            <div>
                Document confidentiel - Usage interne uniquement<br>
                Généré automatiquement par le système Eglix
            </div>
        </div>
    </div>
</body>
</html>
