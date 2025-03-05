<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport de Parrainages - {{ $user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-title {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 10px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        .info-content {
            margin-left: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #ecf0f1;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background-color: #2ecc71;
            border-radius: 10px;
            width: {{ $progress }}%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport de Parrainages</div>
        <div class="subtitle">Généré le {{ $generatedAt->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="info-section">
        <div class="info-title">Informations du Candidat</div>
        <div class="info-content">
            <p><strong>Nom :</strong> {{ $user->name }}</p>
            <p><strong>Parti :</strong> {{ $user->party_name }}</p>
            <p><strong>Position :</strong> {{ $user->party_position }}</p>
            <p><strong>Email :</strong> {{ $user->email }}</p>
        </div>
    </div>

    <div class="info-section">
        <div class="info-title">État des Parrainages</div>
        <div class="info-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Parrainages Validés</h3>
                    <p class="stat-number">{{ $stats['total_validated'] }}</p>
                </div>
                <div class="stat-card">
                    <h3>Parrainages en Attente</h3>
                    <p class="stat-number">{{ $stats['total_pending'] }}</p>
                </div>
                <div class="stat-card">
                    <h3>Parrainages Rejetés</h3>
                    <p class="stat-number">{{ $stats['total_rejected'] }}</p>
                </div>
                <div class="stat-card">
                    <h3>Progression Totale</h3>
                    <p class="stat-number">{{ number_format($progress, 1) }}%</p>
                </div>
            </div>

            <p><strong>Total des parrainages validés :</strong> {{ $sponsorshipsCount }} sur 44 231 requis</p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-title">Répartition par Région</div>
        <table>
            <thead>
                <tr>
                    <th>Région</th>
                    <th>Nombre de Parrainages</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['regions'] as $region => $count)
                    <tr>
                        <td>{{ $region }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <div class="info-title">Derniers Parrainages</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Électeur</th>
                    <th>Région</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sponsorships as $sponsorship)
                    <tr>
                        <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sponsorship->voter->name }}</td>
                        <td>{{ $sponsorship->voter->region->name }}</td>
                        <td>{{ ucfirst($sponsorship->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Ce rapport a été généré automatiquement par le Système Électoral du Sénégal.</p>
        <p>Pour plus d'informations, contactez l'administration.</p>
    </div>
</body>
</html>
