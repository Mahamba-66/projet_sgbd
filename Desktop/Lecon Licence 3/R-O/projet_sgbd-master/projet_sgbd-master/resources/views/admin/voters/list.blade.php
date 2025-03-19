@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des Électeurs</h1>
        <a href="{{ route('admin.voters.import') }}" class="btn btn-primary">
            <i class="fas fa-file-import me-1"></i>
            Importer des électeurs
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Liste des électeurs importés
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
                    <select class="form-select" id="regionFilter">
                        <option value="">Toutes les régions</option>
                        @foreach($voters->unique('region_id') as $voter)
                            @if($voter->region)
                                <option value="{{ $voter->region_id }}">{{ $voter->region->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>CIN</th>
                            <th>N° Électeur</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Date d'ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($voters as $voter)
                        <tr>
                            <td>{{ $voter->name }}</td>
                            <td>{{ $voter->email }}</td>
                            <td>{{ $voter->cin }}</td>
                            <td>{{ $voter->numero_electeur }}</td>
                            <td>{{ $voter->region ? $voter->region->name : 'Non spécifiée' }}</td>
                            <td>
                                <span class="badge bg-{{ $voter->status === 'active' ? 'success' : 'warning' }}">
                                    {{ $voter->status }}
                                </span>
                            </td>
                            <td>{{ $voter->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Affichage de {{ $voters->firstItem() }} à {{ $voters->lastItem() }} sur {{ $voters->total() }} électeurs
                </div>
                <div>
                    {{ $voters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const regionFilter = document.getElementById('regionFilter');
    const tbody = document.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRegion = regionFilter.value;

        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const cin = row.cells[2].textContent.toLowerCase();
            const numero = row.cells[3].textContent.toLowerCase();
            const region = row.cells[4].getAttribute('data-region-id');

            const matchesSearch = name.includes(searchTerm) || 
                                email.includes(searchTerm) || 
                                cin.includes(searchTerm) || 
                                numero.includes(searchTerm);

            const matchesRegion = !selectedRegion || region === selectedRegion;

            row.style.display = matchesSearch && matchesRegion ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    regionFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection
