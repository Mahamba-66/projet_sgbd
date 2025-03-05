@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="h3 mb-2">Mes Parrainages</h1>
            <p class="text-muted">Gérez et suivez vos parrainages</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($sponsorships->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Électeur</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sponsorships as $sponsorship)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($sponsorship->voter->photo)
                                                <img src="{{ Storage::url($sponsorship->voter->photo) }}" 
                                                     alt="{{ $sponsorship->voter->name }}"
                                                     class="rounded-circle me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $sponsorship->voter->name }}</div>
                                                <small class="text-muted">{{ $sponsorship->voter->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge rounded-pill 
                                            @if($sponsorship->status === 'validated') bg-success
                                            @elseif($sponsorship->status === 'rejected') bg-danger
                                            @else bg-warning @endif">
                                            @if($sponsorship->status === 'validated')
                                                Validé
                                            @elseif($sponsorship->status === 'rejected')
                                                Rejeté
                                            @else
                                                En attente
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#sponsorshipModal{{ $sponsorship->id }}">
                                            Détails
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="sponsorshipModal{{ $sponsorship->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails du parrainage</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <h6>Électeur</h6>
                                                    <p>{{ $sponsorship->voter->name }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <h6>Numéro d'électeur</h6>
                                                    <p>{{ $sponsorship->voter->voter_id }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <h6>Date de parrainage</h6>
                                                    <p>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                                @if($sponsorship->status === 'rejected')
                                                    <div class="mb-3">
                                                        <h6>Motif du rejet</h6>
                                                        <p class="text-danger">{{ $sponsorship->rejection_reason }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $sponsorships->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">Vous n'avez pas encore reçu de parrainages.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
