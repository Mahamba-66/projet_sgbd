@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Gestion des Périodes de Parrainage</h2>
        <a href="{{ route('admin.sponsorship-periods.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvelle Période
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Min. Parrainages</th>
                            <th>Max. Parrainages</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periods as $period)
                            <tr>
                                <td>{{ $period->start_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $period->end_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $period->min_sponsorships }}</td>
                                <td>{{ $period->max_sponsorships }}</td>
                                <td>
                                    <span class="badge bg-{{ $period->status === 'active' ? 'success' : 'warning' }}">
                                        {{ $period->status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('admin.sponsorship-periods.toggle-status', $period) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $period->status === 'active' ? 'warning' : 'success' }}" title="{{ $period->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                <i class="bi bi-power"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.sponsorship-periods.edit', $period) }}" class="btn btn-sm btn-info" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.sponsorship-periods.destroy', $period) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucune période de parrainage définie</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 600;
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }
</style>
@endsection
