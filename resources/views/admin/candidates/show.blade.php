@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Détails du Candidat</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.candidates.index') }}">Candidats</a></li>
        <li class="breadcrumb-item active">{{ $candidate->name }}</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informations du Candidat
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom :</strong> {{ $candidate->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email :</strong> {{ $candidate->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Parti :</strong> {{ $candidate->party_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Position :</strong> {{ $candidate->party_position }}
                    </div>
                    <div class="mb-3">
                        <strong>Statut :</strong>
                        @switch($candidate->status)
                            @case('pending')
                                <span class="badge bg-warning">En attente</span>
                                @break
                            @case('validated')
                                <span class="badge bg-success">Validé</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger">Rejeté</span>
                                @break
                        @endswitch
                    </div>
                    @if($candidate->status === 'rejected')
                        <div class="mb-3">
                            <strong>Raison du rejet :</strong>
                            <p class="text-danger">{{ $candidate->blocked_reason }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>Date d'inscription :</strong>
                        {{ $candidate->created_at->format('d/m/Y H:i') }}
                    </div>
                    @if($candidate->validated_at)
                        <div class="mb-3">
                            <strong>Date de validation :</strong>
                            {{ \Carbon\Carbon::parse($candidate->validated_at)->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-handshake me-1"></i>
                    Parrainages ({{ $sponsorships->count() }})
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm reject-btn" 
                                                        data-id="{{ $sponsorship->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le parrainage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Raison du rejet</label>
                        <textarea class="form-control" id="rejection_reason" name="reason" required></textarea>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la validation
        document.querySelectorAll('.validate-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                if (confirm('Voulez-vous vraiment valider ce parrainage ?')) {
                    fetch(`/admin/sponsorships/${id}/validate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                }
            });
        });

        // Gestion du rejet
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('rejectForm').action = `/admin/sponsorships/${id}/reject`;
                rejectModal.show();
            });
        });
    });
</script>
@endpush
@endsection
