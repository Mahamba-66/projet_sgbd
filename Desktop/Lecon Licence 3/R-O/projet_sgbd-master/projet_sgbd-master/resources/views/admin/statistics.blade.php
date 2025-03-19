@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Statistiques</h1>

    <!-- Cartes des statistiques globales -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Candidats</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCandidates }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Électeurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVoters }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Parrainages</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSponsorships }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-signature fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Parrainages Validés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $validatedSponsorships }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Graphique des parrainages par région -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Parrainages par Région</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="sponsorshipsByRegionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 des candidats -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 des Candidats</h6>
                </div>
                <div class="card-body">
                    @foreach($topCandidates as $candidate)
                    <h4 class="small font-weight-bold">
                        {{ $candidate->name }}
                        <span class="float-right">{{ $candidate->validated_count }} parrainages</span>
                    </h4>
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: {{ ($candidate->validated_count / 44231) * 100 }}%"
                            aria-valuenow="{{ ($candidate->validated_count / 44231) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des parrainages par région
    const sponsorshipsByRegion = @json($sponsorshipsByRegion);
    
    new Chart(document.getElementById('sponsorshipsByRegionChart'), {
        type: 'bar',
        data: {
            labels: sponsorshipsByRegion.map(item => item.name),
            datasets: [{
                label: 'Nombre de parrainages',
                data: sponsorshipsByRegion.map(item => item.total),
                backgroundColor: 'rgba(78, 115, 223, 0.5)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
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
@endpush
@endsection
