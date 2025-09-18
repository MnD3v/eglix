<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Financier - {{ now()->format('d/m/Y') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #FF2600;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #FF2600;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header p {
            color: #666;
            margin: 0;
        }
        
        .summary-section {
            margin-bottom: 30px;
        }
        
        .summary-title {
            background: #F9FAFB;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            margin: 0 0 15px 0;
            border-left: 4px solid #FF2600;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-card {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 15px;
            border: 1px solid #E5E7EB;
            margin-right: 2%;
        }
        
        .summary-card:last-child {
            margin-right: 0;
        }
        
        .summary-card h3 {
            color: #FF2600;
            font-size: 14px;
            margin: 0 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #F3F4F6;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #6B7280;
        }
        
        .summary-value {
            font-weight: bold;
            color: #1F2937;
        }
        
        .table-section {
            margin-top: 30px;
        }
        
        .table-title {
            background: #F9FAFB;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            margin: 0 0 15px 0;
            border-left: 4px solid #8B5CF6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #F9FAFB;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #E5E7EB;
        }
        
        td {
            padding: 10px;
            border: 1px solid #E5E7EB;
        }
        
        tr:nth-child(even) {
            background: #F9FAFB;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport Financier</h1>
        <p>Église - {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="summary-section">
        <h2 class="summary-title">Résumé Financier</h2>
        
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Mois en cours</h3>
                <div class="summary-item">
                    <span class="summary-label">Dîmes</span>
                    <span class="summary-value">{{ number_format(round($month['tithes']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Offrandes</span>
                    <span class="summary-value">{{ number_format(round($month['offerings']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dons</span>
                    <span class="summary-value">{{ number_format(round($month['donations']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dépenses</span>
                    <span class="summary-value">{{ number_format(round($month['expenses']), 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            
            <div class="summary-card">
                <h3>Année en cours</h3>
                <div class="summary-item">
                    <span class="summary-label">Dîmes</span>
                    <span class="summary-value">{{ number_format(round($year['tithes']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Offrandes</span>
                    <span class="summary-value">{{ number_format(round($year['offerings']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dons</span>
                    <span class="summary-value">{{ number_format(round($year['donations']), 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dépenses</span>
                    <span class="summary-value">{{ number_format(round($year['expenses']), 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>

    <div class="table-section">
        <h2 class="table-title">Détail par Projet</h2>
        <table>
            <thead>
                <tr>
                    <th>Projet</th>
                    <th>Dons</th>
                    <th>Dépenses</th>
                    <th>Solde</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byProject as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ number_format(round($p->donations_total), 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format(round($p->expenses_total), 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format(round($p->donations_total - $p->expenses_total), 0, ',', ' ') }} FCFA</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #666;">Aucune donnée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>


