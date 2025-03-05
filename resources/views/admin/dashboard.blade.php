@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h2 mb-4">Tableau de Bord</h1>
    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_candidates']) }}</h4>
                    <div>Candidats</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.candidates.index') }}">Voir détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_voters']) }}</h4>
                    <div>Électeurs</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.voters.index') }}">Voir détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['total_sponsorships']) }}</h4>
                    <div>Total Parrainages</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.sponsorships.index') }}">Voir détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4 class="mb-2">{{ number_format($stats['validated_sponsorships']) }}</h4>
                    <div>Parrainages Validés</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.sponsorships.index') }}?status=validated">Voir détails</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    État des Parrainages
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>En attente</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: {{ $stats['total_sponsorships'] > 0 ? ($stats['pending_sponsorships'] / $stats['total_sponsorships'] * 100) : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($stats['pending_sponsorships']) }}</td>
                                </tr>
                                <tr>
                                    <td>Validés</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $stats['total_sponsorships'] > 0 ? ($stats['validated_sponsorships'] / $stats['total_sponsorships'] * 100) : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($stats['validated_sponsorships']) }}</td>
                                </tr>
                                <tr>
                                    <td>Rejetés</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" 
                                                 style="width: {{ $stats['total_sponsorships'] > 0 ? ($stats['rejected_sponsorships'] / $stats['total_sponsorships'] * 100) : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($stats['rejected_sponsorships']) }}</td>
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
