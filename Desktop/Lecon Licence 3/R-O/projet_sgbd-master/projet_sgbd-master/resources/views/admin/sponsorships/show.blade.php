@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails du Parrainage</h1>
        <a href="{{ route('admin.sponsorships.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informations du Candidat
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Nom</th>
                            <td>{{ $sponsorship->candidate_name }}</td>
                        </tr>
                        <tr>
                            <th>Parti politique</th>
                            <td>{{ $sponsorship->party_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-check me-1"></i>
                    Informations de l'Électeur
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Nom</th>
                            <td>{{ $sponsorship->voter_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $sponsorship->voter_email }}</td>
                        </tr>
                        <tr>
                            <th>Région</th>
                            <td>{{ $sponsorship->region_id }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Détails du Parrainage
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 200px;">Statut</th>
                            <td>
                                @if($sponsorship->status === 'validated')
                                    <span class="badge bg-success">Validé</span>
                                @elseif($sponsorship->status === 'rejected')
                                    <span class="badge bg-danger">Rejeté</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date de création</th>
                            <td>{{ $sponsorship->created_at }}</td>
                        </tr>
                        @if($sponsorship->status === 'validated')
                            <tr>
                                <th>Date de validation</th>
                                <td>{{ $sponsorship->validated_at }}</td>
                            </tr>
                        @endif
                        @if($sponsorship->status === 'rejected')
                            <tr>
                                <th>Date de rejet</th>
                                <td>{{ $sponsorship->rejected_at }}</td>
                            </tr>
                            @if($sponsorship->rejection_reason)
                                <tr>
                                    <th>Motif du rejet</th>
                                    <td>{{ $sponsorship->rejection_reason }}</td>
                                </tr>
                            @endif
                        @endif
                    </table>

                    @if($sponsorship->status === 'pending')
                        <div class="mt-4">
                            <form action="{{ route('admin.sponsorships.validate', $sponsorship->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-success me-2" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir valider ce parrainage ?')">
                                    <i class="fas fa-check me-1"></i>
                                    Valider le parrainage
                                </button>
                            </form>

                            <form action="{{ route('admin.sponsorships.reject', $sponsorship->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce parrainage ?')">
                                    <i class="fas fa-times me-1"></i>
                                    Rejeter le parrainage
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
