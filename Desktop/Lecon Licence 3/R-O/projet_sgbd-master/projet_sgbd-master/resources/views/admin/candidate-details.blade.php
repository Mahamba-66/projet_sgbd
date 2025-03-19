@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails du Candidat</h1>
    <div class="row mt-4">
        <!-- Informations du candidat -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informations Personnelles
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom:</strong> {{ $candidate->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Parti:</strong> {{ $candidate->party_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Position:</strong> {{ $candidate->party_position }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $candidate->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong> {{ $candidate->phone }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques des parrainages -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Statistiques des Parrainages
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Progression Globale</h5>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($candidate->validated_sponsorships / 44231) * 100 }}%">
                                        {{ number_format(($candidate->validated_sponsorships / 44231) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Total des Parrainages</h5>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Validés
                                        <span class="badge bg-success rounded-pill">{{ $candidate->validated_sponsorships }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        En attente
                                        <span class="badge bg-warning rounded-pill">{{ $candidate->total_sponsorships - $candidate->validated_sponsorships }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par région -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-map-marker-alt me-1"></i>
            Répartition par Région
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Région</th>
                            <th>Total</th>
                            <th>Validés</th>
                            <th>Progression</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($regionStats as $stat)
                        <tr>
                            <td>{{ $stat->region->name }}</td>
                            <td>{{ $stat->total }}</td>
                            <td>{{ $stat->validated }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($stat->validated / $stat->total) * 100 }}%">
                                        {{ number_format(($stat->validated / $stat->total) * 100, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Liste des parrainages -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list me-1"></i>
            Liste des Parrainages
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="sponsorshipsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Électeur</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sponsorships as $sponsorship)
                        <tr>
                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $sponsorship->voter->name }}</td>
                            <td>{{ $sponsorship->region->name }}</td>
                            <td>
                                @if($sponsorship->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($sponsorship->status === 'validated')
                                    <span class="badge bg-success">Validé</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td>
                                @if($sponsorship->status === 'pending')
                                    <form action="{{ route('admin.sponsorship.validate', $sponsorship->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Valider
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $sponsorship->id }}">
                                        <i class="fas fa-times"></i> Rejeter
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $sponsorships->links() }}
        </div>
    </div>
</div>

<!-- Modals de rejet -->
@foreach($sponsorships as $sponsorship)
    @if($sponsorship->status === 'pending')
    <div class="modal fade" id="rejectModal{{ $sponsorship->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rejeter le parrainage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.sponsorship.reject', $sponsorship->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Raison du rejet</label>
                            <textarea class="form-control" id="reason" name="reason" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#sponsorshipsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        pageLength: 25
    });
});
</script>
@endsection
