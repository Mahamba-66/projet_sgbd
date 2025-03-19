<?php
// Ajouté le 03/19/2025 04:27:31
// feat: Interface admin avec statistiques

@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de bord administrateur</h1>

    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <!-- Total des parrainages -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Parrainages</h6>
                            <h2 class="my-2">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parrainages validés -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Validés</h6>
                            <h2 class="my-2">{{ $stats['validated'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parrainages en attente -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">En attente</h6>
                            <h2 class="my-2">{{ $stats['pending'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parrainages rejetés -->
        <div class="col-md-6 col-lg-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Rejetés</h6>
                            <h2 class="my-2">{{ $stats['rejected'] }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Graphique des parrainages par région -->
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Parrainages par région</h5>
                </div>
                <div class="card-body">
                    <canvas id="regionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top candidats -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Candidats</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($stats['top_candidates'] as $candidate)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $candidate->name }}</h6>
                                    <small class="text-muted">{{ $candidate->party_name }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $candidate->received_sponsorships_count }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des régions
    const regions = @json($stats['by_region']);
    
    new Chart(document.getElementById('regionChart'), {
        type: 'bar',
        data: {
            labels: regions.map(r => r.name),
            datasets: [{
                label: 'Parrainages validés',
                data: regions.map(r => r.sponsorships_count),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush
