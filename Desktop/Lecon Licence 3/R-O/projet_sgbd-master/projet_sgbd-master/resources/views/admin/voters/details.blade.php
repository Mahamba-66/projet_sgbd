@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails de l'Électeur</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.voters.index') }}">Électeurs</a></li>
        <li class="breadcrumb-item active">Détails</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i>
            Informations de l'électeur
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Informations personnelles</h5>
                    <table class="table">
                        <tr>
                            <th>Nom</th>
                            <td>{{ $voter->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $voter->email }}</td>
                        </tr>
                        <tr>
                            <th>Numéro d'électeur</th>
                            <td>{{ $voter->voter_card_number }}</td>
                        </tr>
                        <tr>
                            <th>NIN</th>
                            <td>{{ $voter->nin }}</td>
                        </tr>
                        <tr>
                            <th>Région</th>
                            <td>{{ $voter->region ? $voter->region->name : 'Non spécifiée' }}</td>
                        </tr>
                        <tr>
                            <th>Date d'inscription</th>
                            <td>{{ $voter->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Statistiques de parrainage</h5>
                    <table class="table">
                        <tr>
                            <th>Nombre total de parrainages</th>
                            <td>{{ $voter->sponsorships_count }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
