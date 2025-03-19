@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des Candidats</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Parti</th>
                            <th>Total Parrainages</th>
                            <th>Validés</th>
                            <th>En attente</th>
                            <th>Rejetés</th>
                            <th>Progression</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $candidate)
                            <tr>
                                <td>{{ $candidate->name }}</td>
                                <td>{{ $candidate->party_name ?? 'Non spécifié' }}</td>
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
                                            {{ number_format($candidate->progress, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
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
                                        @default
                                            <span class="badge bg-secondary">{{ $candidate->status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.candidates.show', $candidate->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($candidate->status === 'pending')
                                            <form action="{{ route('admin.candidates.validate', $candidate->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-success btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir valider ce candidat ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.candidates.reject', $candidate->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce candidat ?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.candidates.report', $candidate->id) }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de DataTables si nécessaire
    if ($.fn.DataTable) {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });
    }
});
</script>
@endpush
