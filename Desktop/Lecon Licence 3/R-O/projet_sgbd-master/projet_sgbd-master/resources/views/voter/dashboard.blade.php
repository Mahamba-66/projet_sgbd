@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="h3 mb-4">Tableau de Bord Électeur</h1>
                    <p class="text-muted">
                        Bienvenue dans votre espace électeur.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Liste des Candidats</h5>
                    <p class="card-text text-muted">
                        Consultez la liste des candidats et leurs programmes.
                    </p>
                    <a href="{{ route('voter.candidates.index') }}" class="btn btn-primary">
                        Voir les Candidats
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Mon Profil</h5>
                    <p class="card-text text-muted">
                        Consultez et mettez à jour vos informations.
                    </p>
                    <a href="{{ route('voter.profile') }}" class="btn btn-success">
                        Gérer mon Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>
@endsection
