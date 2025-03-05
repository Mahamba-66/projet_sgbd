@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Rapports</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Rapports</li>
    </ol>

    <div class="row">
        <!-- Graphique d'évolution des parrainages -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Évolution des Parrainages (30 derniers jours)
                </div>
                <div class="card-body">
                    <canvas id="sponsorshipTrendChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Progression des candidats -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Progression des Candidats
                </div>
                <div class="card-body">
                    @foreach($reports['candidate_progress'] as $candidate)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <h6>{{ $candidate->name }}</h6>
                            <span>{{ number_format($candidate->progress, 1) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $candidate->progress }}%">
                                {{ number_format($candidate->validated_sponsorships_count) }}/44231
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par région -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Statistiques par Région
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Région</th>
                        <th>Total Parrainages</th>
                        <th>Parrainages Validés</th>
                        <th>Taux de Validation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports['regional_stats'] as $stat)
                    <tr>
                        <td>{{ $stat->region }}</td>
                        <td>{{ number_format($stat->total) }}</td>
                        <td>{{ number_format($stat->validated) }}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $stat->total > 0 ? ($stat->validated / $stat->total) * 100 : 0 }}%">
                                    {{ number_format($stat->total > 0 ? ($stat->validated / $stat->total) * 100 : 0, 1) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique de tendance
    const trendData = @json($reports['sponsorship_trend']);
    
    // Créer le graphique de tendance
    const ctx = document.getElementById('sponsorshipTrendChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.date),
            datasets: [{
                label: 'Nombre de parrainages',
                data: trendData.map(item => item.total),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection
