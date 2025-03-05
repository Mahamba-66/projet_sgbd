@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Gestion des Électeurs</h2>
        <div>
            <a href="{{ route('admin.voters.import') }}" class="btn btn-success me-2">
                <i class="bi bi-upload"></i> Importer
            </a>
            <a href="{{ route('admin.voters.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvel Électeur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>N° Carte</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voters as $voter)
                        <tr>
                            <td class="align-middle">{{ $voter->name }}</td>
                            <td class="align-middle">{{ $voter->email }}</td>
                            <td class="align-middle">{{ $voter->card_number }}</td>
                            <td class="align-middle">
                                @if($voter->status === 'validated')
                                    <span class="badge bg-success">Validé</span>
                                @elseif($voter->status === 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('admin.voters.edit', $voter->id) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Modifier">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.voters.destroy', $voter->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet électeur ?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($voters->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $voters->links() }}
            </div>
            @endif
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
        padding: 0.75rem;
    }
    
    .table td {
        vertical-align: middle;
        color: #212529;
        padding: 0.75rem;
    }
    
    .btn-group .btn {
        padding: .25rem .5rem;
        font-size: .875rem;
        margin: 0 2px;
    }
    
    .btn-group .btn i {
        font-size: 1rem;
    }
    
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }
    
    .badge.bg-warning {
        color: #000 !important;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-body {
        padding: 1.25rem;
    }

    .pagination {
        margin: 0;
    }

    .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endsection
