@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détails du Parrainage</h5>
                        <div>
                            <a href="{{ route('voter.sponsorships.index') }}" class="btn btn-sm btn-secondary">
                                Retour à la liste
                            </a>
                            <a href="{{ route('voter.dashboard') }}" class="btn btn-sm btn-secondary ms-2">
                                Tableau de bord
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations du Candidat</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Nom</dt>
                                <dd class="col-sm-8">{{ $sponsorship->candidate->name }}</dd>
                                
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8">{{ $sponsorship->candidate->email }}</dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <h6>Informations du Parrainage</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Date</dt>
                                <dd class="col-sm-8">{{ $sponsorship->created_at->format('d/m/Y H:i') }}</dd>
                                
                                <dt class="col-sm-4">Région</dt>
                                <dd class="col-sm-8">{{ $sponsorship->region->name }}</dd>
                                
                                <dt class="col-sm-4">Statut</dt>
                                <dd class="col-sm-8">
                                    @if($sponsorship->status === 'pending')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($sponsorship->status === 'approved')
                                        <span class="badge bg-success">Approuvé</span>
                                    @elseif($sponsorship->status === 'rejected')
                                        <span class="badge bg-danger">Rejeté</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
