@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Parrainages</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Parrainages</li>
    </ol>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($stats['total']) }}</h4>
                    <div>Total Parrainages</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($stats['pending']) }}</h4>
                    <div>En Attente</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($stats['validated']) }}</h4>
                    <div>Validés</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h4>{{ number_format($stats['rejected']) }}</h4>
                    <div>Rejetés</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Liste des Parrainages
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="sponsorshipsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Candidat</th>
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
                            <td>
                                <a href="{{ route('admin.candidates.show', $sponsorship->candidate->id) }}">
                                    {{ $sponsorship->candidate->name }}
                                </a>
                            </td>
                            <td>
                                {{ $sponsorship->voter->name }}
                            </td>
                            <td>{{ $sponsorship->voter->region }}</td>
                            <td>
                                @switch($sponsorship->status)
                                    @case('pending')
                                        <span class="badge bg-warning">En attente</span>
                                        @break
                                    @case('validated')
                                        <span class="badge bg-success">Validé</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger" title="{{ $sponsorship->rejection_reason }}">
                                            Rejeté
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($sponsorship->status === 'pending')
                                    <button class="btn btn-success btn-sm validate-btn" 
                                            data-id="{{ $sponsorship->id }}">
                                        <i class="fas fa-check"></i> Valider
                                    </button>
                                    <button class="btn btn-danger btn-sm reject-btn" 
                                            data-id="{{ $sponsorship->id }}">
                                        <i class="fas fa-times"></i> Rejeter
                                    </button>
                                @else
                                    <button class="btn btn-info btn-sm" 
                                            onclick="showDetails({{ $sponsorship->id }})">
                                        <i class="fas fa-info-circle"></i> Détails
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sponsorships->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Motif du rejet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Motif</label>
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

<!-- Modal de détails -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Parrainage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="sponsorshipDetails"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#sponsorshipsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[0, 'desc']],
        pageLength: 20
    });

    // Gestion de la validation
    $('.validate-btn').click(function() {
        const id = $(this).data('id');
        if(confirm('Êtes-vous sûr de vouloir valider ce parrainage ?')) {
            $.post(`/admin/sponsorship/${id}/validate`)
                .done(function() {
                    location.reload();
                })
                .fail(function(xhr) {
                    alert('Une erreur est survenue');
                });
        }
    });

    // Gestion du rejet
    $('.reject-btn').click(function() {
        const id = $(this).data('id');
        $('#rejectForm').attr('action', `/admin/sponsorship/${id}/reject`);
        $('#rejectModal').modal('show');
    });
});

function showDetails(id) {
    $.get(`/admin/sponsorship/${id}/details`)
        .done(function(data) {
            $('#sponsorshipDetails').html(data);
            $('#detailsModal').modal('show');
        })
        .fail(function(xhr) {
            alert('Une erreur est survenue');
        });
}
</script>
@endsection
