<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Mensuel des Dîmes - {{ now()->format('d/m/Y') }}</title>
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
            border-bottom: 2px solid #22C55E;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #22C55E;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header p {
            color: #666;
            margin: 0;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            background: #F9FAFB;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            margin: 0 0 15px 0;
            border-left: 4px solid #22C55E;
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
            color: #22C55E;
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
        <h1>Rapport Mensuel des Dîmes</h1>
        <p>Église - {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Résumé des Dîmes</h2>
        
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Par mois</h3>
                @if($rows->count() > 0)
                    @foreach($rows as $r)
                    <div class="summary-item">
                        <span class="summary-label">{{ $r->month }}</span>
                        <span class="summary-value">{{ number_format(round($r->total), 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endforeach
                @else
                    <div class="summary-item">
                        <span class="summary-label">Aucune donnée</span>
                        <span class="summary-value">—</span>
                    </div>
                @endif
            </div>
            
            <div class="summary-card">
                <h3>Par membre</h3>
                @if($perMember->count() > 0)
                    @foreach($perMember as $m)
                    <div class="summary-item">
                        <span class="summary-label">{{ $m->member }} ({{ $m->month }})</span>
                        <span class="summary-value">{{ number_format(round($m->total), 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endforeach
                @else
                    <div class="summary-item">
                        <span class="summary-label">Aucune donnée</span>
                        <span class="summary-value">—</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>


