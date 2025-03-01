@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Gestion des parrainages</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="{{ route('candidate.sponsorships') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher un parrain..." value="{{ request('search') }}">
                                    <select name="status" class="form-select" style="width: 150px;">
                                        <option value="">Tous les statuts</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                        <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>Valide</option>
                                        <option value="invalid" {{ request('status') === 'invalid' ? 'selected' : '' }}>Invalide</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Rechercher</button>
                                </form>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('candidate.download-form') }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Télécharger le formulaire
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom du parrain</th>
                                    <th>Numéro électeur</th>
                                    <th>Région</th>
                                    <th>Date de parrainage</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sponsorships as $sponsorship)
                                    <tr>
                                        <td>{{ $sponsorship->voter->full_name }}</td>
                                        <td>{{ $sponsorship->voter->voter_card_number }}</td>
                                        <td>{{ $sponsorship->voter->region }}</td>
                                        <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($sponsorship->status === 'valid')
                                                <span class="badge bg-success">Valide</span>
                                            @elseif($sponsorship->status === 'invalid')
                                                <span class="badge bg-danger">Invalide</span>
                                            @else
                                                <span class="badge bg-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#sponsorshipModal{{ $sponsorship->id }}">
                                                Détails
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal pour les détails -->
                                    <div class="modal fade" id="sponsorshipModal{{ $sponsorship->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails du parrainage</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Informations du parrain</h6>
                                                    <p><strong>Nom complet:</strong> {{ $sponsorship->voter->full_name }}</p>
                                                    <p><strong>Numéro électeur:</strong> {{ $sponsorship->voter->voter_card_number }}</p>
                                                    <p><strong>CNI:</strong> {{ $sponsorship->voter->national_id }}</p>
                                                    <p><strong>Région:</strong> {{ $sponsorship->voter->region }}</p>
                                                    <p><strong>Bureau de vote:</strong> {{ $sponsorship->voter->polling_station }}</p>
                                                    
                                                    <h6 class="mt-4">Statut du parrainage</h6>
                                                    <p><strong>Date de soumission:</strong> {{ $sponsorship->created_at->format('d/m/Y H:i') }}</p>
                                                    <p><strong>Statut actuel:</strong> 
                                                        @if($sponsorship->status === 'valid')
                                                            <span class="badge bg-success">Valide</span>
                                                        @elseif($sponsorship->status === 'invalid')
                                                            <span class="badge bg-danger">Invalide</span>
                                                            @if($sponsorship->rejection_reason)
                                                                <p class="text-danger">Raison: {{ $sponsorship->rejection_reason }}</p>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-warning">En attente</span>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun parrainage trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $sponsorships->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
