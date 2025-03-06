@extends('layouts.welcome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Choisir le type d'inscription</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-circle fa-3x mb-3 text-primary"></i>
                                    <h5 class="card-title">Électeur</h5>
                                    <p class="card-text">Inscrivez-vous en tant qu'électeur pour parrainer un candidat</p>
                                    <a href="{{ route('register.voter') }}" class="btn btn-primary">
                                        S'inscrire comme électeur
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-tie fa-3x mb-3 text-success"></i>
                                    <h5 class="card-title">Candidat</h5>
                                    <p class="card-text">Inscrivez-vous en tant que candidat pour recevoir des parrainages</p>
                                    <a href="{{ route('register.candidate') }}" class="btn btn-success">
                                        S'inscrire comme candidat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p>Déjà inscrit ?</p>
                        <a href="{{ route('login') }}" class="btn btn-link">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
