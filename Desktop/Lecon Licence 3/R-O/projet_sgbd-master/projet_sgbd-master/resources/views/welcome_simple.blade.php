@extends('layouts.welcome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Système Électoral du Sénégal</h1>
                <p class="lead">Plateforme officielle pour les élections</p>
            </div>

            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2>Choisissez votre rôle</h2>
                        <p>Inscrivez-vous ou connectez-vous selon votre profil</p>
                    </div>

                    <div class="row g-4">
                        <!-- Électeur -->
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3">Électeur</h3>
                                    <p>Participez aux élections en tant que citoyen</p>
                                    <a href="{{ route('register.voter') }}" class="btn btn-primary">
                                        Inscription Électeur
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Candidat -->
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3">Candidat</h3>
                                    <p>Présentez-vous aux élections</p>
                                    <a href="{{ route('register.candidate') }}" class="btn btn-success">
                                        Inscription Candidat
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Admin -->
                        <div class="col-md-4">
                            <div class="card h-100 border-danger">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3">Admin</h3>
                                    <p>Gérez le système électoral</p>
                                    <a href="{{ route('register.admin') }}" class="btn btn-danger">
                                        Inscription Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p>Déjà inscrit ?</p>
                        <a href="{{ route('login') }}" class="btn btn-lg btn-outline-primary">
                            Se connecter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section des fonctionnalités -->
            <div class="row mt-5 g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="h5">Sécurisé</h3>
                            <p class="mb-0">Protection des données et authentification forte</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="h5">Fiable</h3>
                            <p class="mb-0">Système de vérification robuste</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h3 class="h5">Accessible</h3>
                            <p class="mb-0">Interface simple et intuitive</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
