@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Carte de bienvenue -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Bienvenue {{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" class="img-fluid rounded" alt="Photo de profil">
                            @else
                                <div class="bg-secondary text-white rounded p-3 text-center">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h5>Informations personnelles</h5>
                            <p><strong>Numéro électeur :</strong> {{ $user->voter_card_number }}</p>
                            <p><strong>NIN :</strong> {{ $user->nin }}</p>
                            <p><strong>Région :</strong> {{ $user->region }}</p>
                            <p><strong>Bureau de vote :</strong> {{ $user->polling_station }}</p>
                            <a href="{{ route('voter.profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Modifier mon profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des parrainages -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Mes parrainages</h4>
                </div>
                <div class="card-body">
                    @if($sponsorships->isEmpty())
                        <div class="alert alert-info">
                            Vous n'avez pas encore parrainé de candidat.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Candidat</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsorships as $sponsorship)
                                        <tr>
                                            <td>{{ $sponsorship->candidate->name }}</td>
                                            <td>
                                                @if($sponsorship->status == 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($sponsorship->status == 'valid')
                                                    <span class="badge bg-success">Validé</span>
                                                @else
                                                    <span class="badge bg-danger">Invalide</span>
                                                @endif
                                            </td>
                                            <td>{{ $sponsorship->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($sponsorship->status == 'pending')
                                                    <form action="{{ route('voter.cancel-sponsorship', $sponsorship->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce parrainage ?')">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des candidats disponibles -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Candidats disponibles pour parrainage</h4>
                </div>
                <div class="card-body">
                    @if($candidates->isEmpty())
                        <div class="alert alert-info">
                            Aucun candidat n'est disponible pour le parrainage.
                        </div>
                    @else
                        <div class="row">
                            @foreach($candidates as $candidate)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        @if($candidate->photo)
                                            <img src="{{ asset('storage/'.$candidate->photo) }}" class="card-img-top" alt="{{ $candidate->name }}">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $candidate->name }}</h5>
                                            <p class="card-text">
                                                <strong>Parti :</strong> {{ $candidate->party_name }}<br>
                                                @if($candidate->biography)
                                                    <small>{{ Str::limit($candidate->biography, 100) }}</small>
                                                @endif
                                            </p>
                                            <form action="{{ route('voter.sponsor') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                                <button type="submit" class="btn btn-primary w-100" 
                                                    {{ $sponsorships->where('candidate_id', $candidate->id)->count() > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-handshake"></i> 
                                                    {{ $sponsorships->where('candidate_id', $candidate->id)->count() > 0 ? 'Déjà parrainé' : 'Parrainer' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
