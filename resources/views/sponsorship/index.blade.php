@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Liste des parrainages</h4>
                    <a href="{{ route('sponsorship.upload') }}" class="btn btn-primary">
                        Importer des parrainages
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NIN</th>
                                    <th>Nom complet</th>
                                    <th>Région</th>
                                    <th>Commune</th>
                                    <th>Statut</th>
                                    <th>Date de validation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sponsorships as $sponsorship)
                                    <tr>
                                        <td>{{ $sponsorship->voter_nin }}</td>
                                        <td>{{ $sponsorship->voter_first_name }} {{ $sponsorship->voter_last_name }}</td>
                                        <td>{{ $sponsorship->voter_region }}</td>
                                        <td>{{ $sponsorship->voter_commune }}</td>
                                        <td>
                                            @switch($sponsorship->status)
                                                @case('valid')
                                                    <span class="badge bg-success">Valide</span>
                                                    @break
                                                @case('invalid')
                                                    <span class="badge bg-danger">Invalide</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning">En attente</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $sponsorship->validation_date ? $sponsorship->validation_date->format('d/m/Y H:i') : 'Non validé' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('sponsorship.show', $sponsorship) }}" 
                                               class="btn btn-sm btn-info">
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Aucun parrainage trouvé.
                                            <a href="{{ route('sponsorship.upload') }}">Importer des parrainages</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $sponsorships->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
