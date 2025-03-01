@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Système de Parrainage</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.electoral-file') }}">Fichier électoral</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sponsorship-periods.index') }}">Périodes de parrainage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button class="btn btn-outline-light" type="submit">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Tableau de bord Administrateur</h1>
    
    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Électeurs</h5>
                    <p class="display-4">{{ $stats['total_voters'] }}</p>
                    <p class="card-text">Électeurs inscrits</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Candidats</h5>
                    <p class="display-4">{{ $stats['total_candidates'] }}</p>
                    <p class="card-text">Candidats inscrits</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Parrainages</h5>
                    <p class="display-4">{{ $stats['total_sponsorships'] }}</p>
                    <p class="card-text">Total des parrainages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card {{ $stats['is_active_period'] ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h5 class="card-title">État du Parrainage</h5>
                    <p class="display-4">{{ $stats['is_active_period'] ? 'Actif' : 'Inactif' }}</p>
                    <p class="card-text">Période de parrainage</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestion du Fichier Électoral</h5>
                    <p class="card-text">Gérer le fichier électoral et les électeurs</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.electoral-file') }}" class="btn btn-primary">Importer fichier électoral</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success">Gérer les utilisateurs</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gestion des Parrainages</h5>
                    <p class="card-text">Superviser le processus de parrainage</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.sponsorship-periods.index') }}" class="btn btn-primary">Gérer les périodes</a>
                        <a href="{{ route('admin.sponsorships.index') }}" class="btn btn-success">Voir les parrainages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
