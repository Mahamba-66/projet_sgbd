@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Erreurs de Validation</h1>
        <a href="{{ route('admin.voters.import') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Retour à l'importation
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Liste des erreurs de validation
        </div>
        <div class="card-body">
            @if(empty($errors))
                <div class="alert alert-info">
                    Aucune erreur de validation n'a été trouvée.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ligne</th>
                                <th>Colonne</th>
                                <th>Valeur</th>
                                <th>Erreur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errors as $error)
                                <tr>
                                    <td>{{ $error['line'] }}</td>
                                    <td>{{ $error['column'] }}</td>
                                    <td>{{ $error['value'] }}</td>
                                    <td>{{ $error['message'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-warning mt-4">
                    <h5><i class="fas fa-info-circle me-1"></i> Instructions :</h5>
                    <ol>
                        <li>Corrigez les erreurs dans votre fichier CSV</li>
                        <li>Assurez-vous que toutes les données sont au bon format</li>
                        <li>Réessayez l'importation avec le fichier corrigé</li>
                    </ol>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
