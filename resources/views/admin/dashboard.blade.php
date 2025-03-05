@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tableau de Bord Administrateur</h1>
    
    <!-- Cartes de statistiques -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4>{{ $stats['total_candidates'] }}</h4>
                    <div>Candidats Total</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>{{ $stats['total_voters'] }}</h4>
                    <div>Électeurs Inscrits</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4>{{ $stats['total_sponsorships'] }}</h4>
                    <div>Parrainages Total</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <h4>{{ $stats['validated_sponsorships'] }}</h4>
                    <div>Parrainages Validés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des candidats -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Liste des Candidats
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="candidatesTable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Parti</th>
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
                            <td>{{ $candidate->party_name }}</td>
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
                                <a href="{{ route('admin.candidates.show', $candidate->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                <a href="{{ route('admin.candidates.report', $candidate->id) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Rapport
                                </a>
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

@section('scripts')
<script>
$(document).ready(function() {
    $('#candidatesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        }
    });
});
</script>
@endsection
