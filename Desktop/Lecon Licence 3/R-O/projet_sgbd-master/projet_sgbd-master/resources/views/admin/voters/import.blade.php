@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Importation des Électeurs</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Importer un fichier CSV</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.voters.import.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="voter_file" class="form-label">Fichier CSV des électeurs</label>
                            <input type="file" class="form-control" id="voter_file" name="voter_file" accept=".csv">
                            <div class="form-text">
                                Le fichier doit être au format CSV avec une seule colonne : numero_electeur
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des importations</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fichier</th>
                                    <th>Total</th>
                                    <th>Valides</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($importHistory as $history)
                                <tr>
                                    <td>{{ $history->file_name }}</td>
                                    <td>{{ $history->total_records }}</td>
                                    <td>{{ $history->valid_records }}</td>
                                    <td>{{ $history->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Numéros de carte d'électeur importés</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Numéro de carte</th>
                            <th>Statut</th>
                            <th>Date d'importation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($importedCards as $card)
                        <tr>
                            <td>{{ $card->voter_card_number }}</td>
                            <td>
                                @if($card->is_used)
                                    <span class="badge badge-success">Utilisé</span>
                                @else
                                    <span class="badge badge-secondary">Non utilisé</span>
                                @endif
                            </td>
                            <td>{{ $card->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $importedCards->links() }}
        </div>
    </div>
</div>
@endsection
