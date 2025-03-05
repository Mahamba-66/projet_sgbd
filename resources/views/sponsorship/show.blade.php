@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails du parrainage</h4>
                    <a href="{{ route('sponsorship.index') }}" class="btn btn-secondary">
                        Retour à la liste
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informations du parrain</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>NIN :</th>
                                    <td>{{ $sponsorship->voter_nin }}</td>
                                </tr>
                                <tr>
                                    <th>Nom complet :</th>
                                    <td>{{ $sponsorship->voter_first_name }} {{ $sponsorship->voter_last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance :</th>
                                    <td>{{ $sponsorship->voter_birthdate->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Genre :</th>
                                    <td>{{ $sponsorship->voter_gender === 'M' ? 'Homme' : 'Femme' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informations électorales</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>Région :</th>
                                    <td>{{ $sponsorship->voter_region }}</td>
                                </tr>
                                <tr>
                                    <th>Département :</th>
                                    <td>{{ $sponsorship->voter_department }}</td>
                                </tr>
                                <tr>
                                    <th>Commune :</th>
                                    <td>{{ $sponsorship->voter_commune }}</td>
                                </tr>
                                <tr>
                                    <th>Bureau de vote :</th>
                                    <td>{{ $sponsorship->voter_polling_station }}</td>
                                </tr>
                                <tr>
                                    <th>N° Carte électeur :</th>
                                    <td>{{ $sponsorship->voter_card_number }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Statut de validation</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Statut actuel :</strong>
                                @switch($sponsorship->status)
                                    @case('valid')
                                        <span class="badge bg-success">Valide</span>
                                        @break
                                    @case('invalid')
                                        <span class="badge bg-danger">Invalide</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning">En attente</span>
                                @endswitch
                            </div>

                            @if($sponsorship->validation_date)
                                <div class="mb-3">
                                    <strong>Date de validation :</strong>
                                    {{ $sponsorship->validation_date->format('d/m/Y H:i') }}
                                </div>
                            @endif

                            @if($sponsorship->validation_comment)
                                <div class="mb-3">
                                    <strong>Commentaire :</strong>
                                    {{ $sponsorship->validation_comment }}
                                </div>
                            @endif

                            @if($sponsorship->status === 'pending')
                                <form action="{{ route('sponsorship.validate', $sponsorship) }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Valider le parrainage</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="status" value="valid" class="btn btn-success">
                                                Valider
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="collapse" 
                                                    data-bs-target="#rejectForm">
                                                Rejeter
                                            </button>
                                        </div>
                                    </div>

                                    <div class="collapse" id="rejectForm">
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Motif du rejet</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" name="status" value="invalid" class="btn btn-danger">
                                            Confirmer le rejet
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
