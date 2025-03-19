<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport du Candidat {{ $candidate->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .stats {
            margin: 20px 0;
            padding: 10px;
            background: #f5f5f5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Candidature</h1>
        <h2>{{ $candidate->name }}</h2>
    </div>

    <div class="section">
        <h3>Informations du Candidat</h3>
        <p><strong>Nom:</strong> {{ $candidate->name }}</p>
        <p><strong>Email:</strong> {{ $candidate->email }}</p>
        <p><strong>Statut:</strong> {{ ucfirst($candidate->status) }}</p>
        <p><strong>Date d'inscription:</strong> {{ $candidate->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="stats">
        <h3>Statistiques des Parrainages</h3>
        <p><strong>Total des parrainages:</strong> {{ $total_sponsorships }}</p>
        <p><strong>Parrainages validés:</strong> {{ $validated_sponsorships }}</p>
        <p><strong>Parrainages en attente:</strong> {{ $pending_sponsorships }}</p>
        <p><strong>Parrainages rejetés:</strong> {{ $rejected_sponsorships }}</p>
    </div>

    <div class="section">
        <h3>Liste des Parrainages</h3>
        <table>
            <thead>
                <tr>
                    <th>Électeur</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sponsorships as $sponsorship)
                <tr>
                    <td>{{ $sponsorship->voter->name }}</td>
                    <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($sponsorship->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Rapport généré le {{ $generated_at->format('d/m/Y à H:i') }}</p>
        <p>Système de Gestion des Parrainages</p>
    </div>
</body>
</html>
