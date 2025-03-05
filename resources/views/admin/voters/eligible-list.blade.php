@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Liste des Électeurs Éligibles</h1>

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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-users me-1"></i>
                Électeurs Éligibles
            </div>
            <div>
                <a href="{{ route('admin.voters.import') }}" class="btn btn-primary">
                    <i class="fas fa-file-import me-1"></i> Importer Nouvelle Liste
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($eligibleVoters->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Aucun électeur éligible n'a encore été importé.
                    <a href="{{ route('admin.voters.import') }}" class="alert-link">Importer une liste</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="eligibleVotersTable">
                        <thead>
                            <tr>
                                <th>Prénom</th>
                                <th>Nom</th>
                                <th>Numéro de Carte</th>
                                <th>Statut</th>
                                <th>Date d'Import</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eligibleVoters as $voter)
                                <tr>
                                    <td>{{ $voter->first_name }}</td>
                                    <td>{{ $voter->last_name }}</td>
                                    <td>{{ $voter->card_number }}</td>
                                    <td>
                                        @if($voter->is_registered)
                                            <span class="badge bg-success">Inscrit</span>
                                        @else
                                            <span class="badge bg-warning">Non Inscrit</span>
                                        @endif
                                    </td>
                                    <td>{{ $voter->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <div class="alert alert-info">
                        <strong>Total :</strong> {{ $eligibleVoters->count() }} électeurs éligibles
                        <br>
                        <strong>Inscrits :</strong> {{ $eligibleVoters->where('is_registered', true)->count() }} électeurs
                        <br>
                        <strong>Non Inscrits :</strong> {{ $eligibleVoters->where('is_registered', false)->count() }} électeurs
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#eligibleVotersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            },
            "order": [[4, 'desc']]
        });
    });
</script>
@endpush
@endsection
