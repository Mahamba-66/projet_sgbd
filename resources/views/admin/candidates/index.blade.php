@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Gestion des Candidats</h2>
        <a href="{{ route('admin.candidates.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau Candidat
        </a>
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
                            <th>Parti</th>
                            <th>Position</th>
                            <th>Statut</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($candidates as $candidate)
                        <tr>
                            <td class="align-middle">{{ $candidate->name }}</td>
                            <td class="align-middle">{{ $candidate->email }}</td>
                            <td class="align-middle">{{ $candidate->party_name }}</td>
                            <td class="align-middle">{{ $candidate->party_position }}</td>
                            <td class="align-middle">
                                @if($candidate->status === 'validated')
                                    <span class="badge bg-success">Validé</span>
                                @elseif($candidate->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $candidate->created_at->format('d/m/Y H:i') }}</td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('admin.candidates.show', $candidate) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.candidates.edit', $candidate) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($candidate->status !== 'validated')
                                        <form action="{{ route('admin.candidates.validate', $candidate) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.candidates.destroy', $candidate) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun candidat trouvé</td>
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
    
    .table td {
        vertical-align: middle;
        color: #212529;
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
    
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-body {
        padding: 1.25rem;
    }
</style>
@endsection
