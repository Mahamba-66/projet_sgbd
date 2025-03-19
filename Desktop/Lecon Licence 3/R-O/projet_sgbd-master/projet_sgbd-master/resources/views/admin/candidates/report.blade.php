<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport du Candidat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport du Candidat</h1>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2>Informations du Candidat</h2>
        <table class="table">
            <tr>
                <th>Nom</th>
                <td>{{ $candidate->name }}</td>
            </tr>
            <tr>
                <th>Parti</th>
                <td>{{ $candidate->party_name }}</td>
            </tr>
            <tr>
                <th>Région</th>
                <td>{{ $candidate->region->name }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>{{ ucfirst($candidate->status) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Statistiques des Parrainages</h2>
        <table class="table">
            <tr>
                <th>Total des parrainages</th>
                <td>{{ $candidate->sponsorships->count() }}</td>
            </tr>
            <tr>
                <th>Parrainages validés</th>
                <td>{{ $candidate->sponsorships->where('status', 'validated')->count() }}</td>
            </tr>
            <tr>
                <th>Parrainages en attente</th>
                <td>{{ $candidate->sponsorships->where('status', 'pending')->count() }}</td>
            </tr>
            <tr>
                <th>Parrainages rejetés</th>
                <td>{{ $candidate->sponsorships->where('status', 'rejected')->count() }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Répartition des Parrainages par Région</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Région</th>
                    <th>Nombre de parrainages</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sponsorshipsByRegion = $candidate->sponsorships
                        ->groupBy('voter.region.name')
                        ->map->count();
                @endphp
                
                @foreach($sponsorshipsByRegion as $region => $count)
                <tr>
                    <td>{{ $region }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
