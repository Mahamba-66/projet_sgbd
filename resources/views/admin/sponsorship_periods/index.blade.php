@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Périodes de Parrainage</h1>
        <a href="{{ route('admin.sponsorship-periods.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouvelle Période
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
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Min. Parrainages</th>
                            <th>Max. Parrainages</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                        <tr>
                            <td>{{ $period->start_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $period->end_date->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($period->min_sponsorships) }}</td>
                            <td>{{ number_format($period->max_sponsorships) }}</td>
                            <td>
                                <span class="badge bg-{{ $period->is_active ? 'success' : 'secondary' }}">
                                    {{ $period->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.sponsorship-periods.edit', $period) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.sponsorship-periods.destroy', $period) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette période ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $periods->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
