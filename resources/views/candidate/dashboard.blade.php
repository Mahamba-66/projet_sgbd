@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tableau de bord du candidat</h4>
                </div>

                <div class="card-body">
                    @if($currentPeriod)
                        <div class="alert alert-info">
                            <h5>Période de parrainage en cours</h5>
                            <p>Du {{ $currentPeriod->start_date->format('d/m/Y') }} au {{ $currentPeriod->end_date->format('d/m/Y') }}</p>
                            <p>Minimum requis : {{ $currentPeriod->min_sponsorships }} parrainages</p>
                            <p>Maximum autorisé : {{ $currentPeriod->max_sponsorships }} parrainages</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Aucune période de parrainage n'est actuellement active.
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Parrainages</h5>
                                    <p class="card-text display-4">{{ $stats['total_sponsorships'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Parrainages Valides</h5>
                                    <p class="card-text display-4">{{ $stats['valid_sponsorships'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Parrainages Invalides</h5>
                                    <p class="card-text display-4">{{ $stats['invalid_sponsorships'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">En Attente</h5>
                                    <p class="card-text display-4">{{ $stats['pending_sponsorships'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Actions rapides</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="{{ route('candidate.sponsorships') }}" class="btn btn-primary btn-lg btn-block mb-3">
                                                <i class="bi bi-list-check"></i> Gérer les parrainages
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('candidate.profile') }}" class="btn btn-info btn-lg btn-block mb-3">
                                                <i class="bi bi-person"></i> Mettre à jour le profil
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('candidate.statistics') }}" class="btn btn-success btn-lg btn-block mb-3">
                                                <i class="bi bi-graph-up"></i> Voir les statistiques détaillées
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
