@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gestion des Candidats</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Candidats</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="candidatesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Total Parrainages</th>
                            <th>Validés</th>
                            <th>En attente</th>
                            <th>Rejetés</th>
                            <th>Progression</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $candidate)
                        <tr>
                            <td>{{ $candidate->name }}</td>
                            <td>{{ $candidate->total_sponsorships }}</td>
                            <td>{{ $candidate->validated_sponsorships }}</td>
                            <td>{{ $candidate->pending_sponsorships }}</td>
                            <td>{{ $candidate->rejected_sponsorships }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $candidate->progress }}%"
                                         aria-valuenow="{{ $candidate->progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $candidate->progress }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                    
                                    @if($candidate->status === 'pending')
                                        <a href="{{ route('admin.candidates.verify', $candidate->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-check-circle"></i> Vérifier
                                        </a>
                                    @elseif($candidate->status === 'verified')
                                        <a href="{{ route('admin.candidates.validate', $candidate->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Valider
                                        </a>
                                        <a href="{{ route('admin.candidates.rollback', $candidate->id) }}" 
                                           class="btn btn-warning btn-sm"
                                           onclick="return confirm('Êtes-vous sûr de vouloir annuler la vérification ?')">
                                            <i class="fas fa-undo"></i> Annuler
                                        </a>
                                    @elseif($candidate->status === 'validated')
                                        <span class="badge bg-success">Validé</span>
                                        <a href="{{ route('admin.candidates.rollback', $candidate->id) }}" 
                                           class="btn btn-warning btn-sm"
                                           onclick="return confirm('Êtes-vous sûr de vouloir annuler la validation ?')">
                                            <i class="fas fa-undo"></i> Annuler
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#candidatesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        }
    });
});
</script>
@endpush
@endsection
