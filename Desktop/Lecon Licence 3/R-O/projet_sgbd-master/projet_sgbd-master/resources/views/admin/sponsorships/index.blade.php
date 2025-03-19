@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Gestion des Parrainages</h1>

    <!-- Période de parrainage active -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-clock me-1"></i> Période de Parrainage Actuelle
        </div>
        <div class="card-body">
            @if($activePeriod)
                <div class="alert alert-success mb-0">
                    <strong>Période active :</strong> 
                    du {{ $activePeriod->start_date->format('d/m/Y H:i') }} 
                    au {{ $activePeriod->end_date->format('d/m/Y H:i') }}
                    @if($activePeriod->description)
                        <br>
                        <small>{{ $activePeriod->description }}</small>
                    @endif
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Aucune période de parrainage n'est actuellement active.
                    <a href="{{ route('admin.sponsorship-periods.create') }}" class="alert-link">
                        Définir une période
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques des parrainages -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            <div>Total des parrainages</div>
                        </div>
                        <i class="fas fa-file-signature fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $stats['validated'] }}</h4>
                            <div>Parrainages validés</div>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                            <div>Parrainages en attente</div>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
                            <div>Parrainages rejetés</div>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des parrainages -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Liste des Parrainages
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Candidat</th>
                            <th>Électeur</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsorships as $sponsorship)
                            <tr>
                                <td>{{ $sponsorship->id }}</td>
                                <td>{{ $sponsorship->candidate_name }}</td>
                                <td>{{ $sponsorship->voter_name }}</td>
                                <td>{{ $sponsorship->region_name }}</td>
                                <td>
                                    @switch($sponsorship->status)
                                        @case('validated')
                                            <span class="badge bg-success">Validé</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">En attente</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejeté</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $sponsorship->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($sponsorship->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($sponsorship->status === 'pending')
                                            <form action="{{ route('admin.sponsorships.validate', ['sponsorship' => $sponsorship->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.sponsorships.reject', ['sponsorship' => $sponsorship->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Rejeter">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.sponsorships.show', $sponsorship->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun parrainage trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($sponsorships->hasPages())
                <div class="mt-4">
                    {{ $sponsorships->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
