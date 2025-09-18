<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Transparence - {{ now()->format('d/m/Y') }}</title>
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
            border-bottom: 2px solid #8B5CF6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #8B5CF6;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header p {
            color: #666;
            margin: 0;
        }
        
        .section-title {
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
        
        .positive {
            color: #22C55E;
            font-weight: bold;
        }
        
        .negative {
            color: #EF4444;
            font-weight: bold;
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
        <h1>Rapport de Transparence</h1>
        <p>Église - {{ now()->format('d/m/Y') }}</p>
    </div>

    <h2 class="section-title">Transactions Récentes</h2>
    
    @if($entries->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Détails</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr>
                    <td>{{ $entry['date'] }}</td>
                    <td>{{ $entry['type'] }}</td>
                    <td>{{ $entry['detail'] }}</td>
                    <td class="{{ $entry['sign'] === '+' ? 'positive' : 'negative' }}">
                        {{ $entry['sign'] }}{{ number_format(round($entry['amount']), 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666; padding: 20px;">
            Aucune transaction enregistrée pour le moment.
        </p>
    @endif

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>


