@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Gestion des Périodes de Stage</h1>
        <a href="{{ route('admin.stages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Période
        </a>
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
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stages as $stage)
                            <tr>
                                <td>{{ $stage->name }}</td>
                                <td>{{ $stage->start_date->format('d/m/Y H:i') }}</td>
                                <td>{{ $stage->end_date->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($stage->status === 'upcoming')
                                        <span class="badge bg-info">À venir</span>
                                    @elseif($stage->status === 'active')
                                        <span class="badge bg-success">En cours</span>
                                    @else
                                        <span class="badge bg-secondary">Terminé</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.stages.edit', $stage) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.stages.destroy', $stage) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
