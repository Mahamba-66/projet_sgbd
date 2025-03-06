@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Électeurs</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Électeurs</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Liste des Électeurs
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="votersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Numéro d'électeur</th>
                            <th>Région</th>
                            <th>Parrainages</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voters as $voter)
                        <tr>
                            <td>{{ $voter->id }}</td>
                            <td>{{ $voter->name }}</td>
                            <td>{{ $voter->voter_id }}</td>
                            <td>{{ is_string($voter->region) ? json_decode($voter->region)->name : ($voter->region ? $voter->region->name : 'Non spécifiée') }}</td>
                            <td>{{ $voter->sponsorships_count }}</td>
                            <td>{{ $voter->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.voters.show', $voter->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                <a href="{{ route('admin.voters.verify', $voter->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-check-circle"></i> Vérifier
                                </a>
                                <a href="{{ route('admin.voters.validate', $voter->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Valider
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $voters->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#votersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[0, 'desc']],
        pageLength: 15
    });
});
</script>
@endsection
