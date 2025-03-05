@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tableau de bord des statistiques</h1>
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>Électeurs</h4>
                    <h2>{{ $voterCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>Candidats</h4>
                    <h2>{{ $candidateCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>Total Parrainages</h4>
                    <h2>{{ $sponsorshipCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h4>Parrainages en attente</h4>
                    <h2>{{ $pendingSponsorshipCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    État des parrainages
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Validés</td>
                                    <td>{{ $validatedSponsorshipCount }}</td>
                                </tr>
                                <tr>
                                    <td>En attente</td>
                                    <td>{{ $pendingSponsorshipCount }}</td>
                                </tr>
                                <tr>
                                    <td>Rejetés</td>
                                    <td>{{ $rejectedSponsorshipCount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
