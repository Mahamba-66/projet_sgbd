@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Statistiques des parrainages</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Statistiques par région -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Répartition par région</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Région</th>
                                                    <th>Nombre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalByRegion = $stats['by_region']->sum('total');
                                                @endphp
                                                @foreach($stats['by_region'] as $region)
                                                    <tr>
                                                        <td>{{ $region->region }}</td>
                                                        <td>{{ $region->total }}</td>
                                                        <td>
                                                            @if($totalByRegion > 0)
                                                                {{ number_format(($region->total / $totalByRegion) * 100, 1) }}%
                                                            @else
                                                                0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques par tranche d'âge -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Répartition par âge</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tranche d'âge</th>
                                                    <th>Nombre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalByAge = $stats['by_age_group']->sum('total');
                                                @endphp
                                                @foreach($stats['by_age_group'] as $age)
                                                    <tr>
                                                        <td>{{ $age->age_group }}</td>
                                                        <td>{{ $age->total }}</td>
                                                        <td>
                                                            @if($totalByAge > 0)
                                                                {{ number_format(($age->total / $totalByAge) * 100, 1) }}%
                                                            @else
                                                                0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques par genre -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Répartition par genre</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Genre</th>
                                                    <th>Nombre</th>
                                                    <th>Pourcentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalByGender = $stats['by_gender']->sum('total');
                                                @endphp
                                                @foreach($stats['by_gender'] as $gender)
                                                    <tr>
                                                        <td>{{ $gender->gender === 'M' ? 'Homme' : 'Femme' }}</td>
                                                        <td>{{ $gender->total }}</td>
                                                        <td>
                                                            @if($totalByGender > 0)
                                                                {{ number_format(($gender->total / $totalByGender) * 100, 1) }}%
                                                            @else
                                                                0%
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tendance journalière -->
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Tendance journalière des parrainages</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Nombre de parrainages</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stats['daily_trend'] as $trend)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($trend->date)->format('d/m/Y') }}</td>
                                                        <td>{{ $trend->total }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('candidate.dashboard') }}" class="btn btn-primary">
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
