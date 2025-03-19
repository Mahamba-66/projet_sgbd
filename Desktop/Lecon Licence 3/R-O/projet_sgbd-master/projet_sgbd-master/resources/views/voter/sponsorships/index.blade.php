@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mes Parrainages</h5>
                        <a href="{{ route('voter.dashboard') }}" class="btn btn-sm btn-secondary">
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>

                <div class="card-body">
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

                    @if($sponsorships->isEmpty())
                        <div class="alert alert-info">
                            Vous n'avez encore parrainé aucun candidat.
                            <a href="{{ route('voter.candidates.index') }}" class="alert-link">Voir la liste des candidats</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Candidat</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsorships as $sponsorship)
                                        <tr>
                                            <td>{{ $sponsorship->candidate->name }}</td>
                                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($sponsorship->status === 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($sponsorship->status === 'approved')
                                                    <span class="badge bg-success">Approuvé</span>
                                                @elseif($sponsorship->status === 'rejected')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('voter.sponsorships.show', $sponsorship->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    Détails
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
