@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Résultats de la validation du fichier</div>

                <div class="card-body">
                    <h4>Fichier : {{ $electoralFile->original_filename }}</h4>
                    <p>Status : {{ $electoralFile->status }}</p>
                    <p>Total des enregistrements : {{ $electoralFile->total_records }}</p>
                    <p>Enregistrements traités : {{ $electoralFile->processed_records }}</p>
                    <p>Enregistrements valides : {{ $electoralFile->valid_records }}</p>
                    <p>Enregistrements invalides : {{ $electoralFile->invalid_records }}</p>

                    @if(!empty($errors))
                        <h5 class="text-danger">Erreurs :</h5>
                        <ul class="list-group">
                            @foreach($errors as $error)
                                <li class="list-group-item list-group-item-danger">
                                    {{ is_array($error) ? $error['message'] : $error }}
                                    @if(isset($error['line']))
                                        (Ligne {{ $error['line'] }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if(!empty($duplicates))
                        <h5 class="text-warning mt-3">Doublons détectés :</h5>
                        <ul class="list-group">
                            @foreach($duplicates as $duplicate)
                                <li class="list-group-item list-group-item-warning">
                                    {{ $duplicate['type'] === 'voter_card' ? 'Carte d\'électeur' : 'CNI' }} : 
                                    {{ $duplicate['value'] }} 
                                    (Ligne {{ $duplicate['line'] }})
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($isValid)
                        <div class="alert alert-success mt-3">
                            Le fichier a été validé avec succès !
                        </div>
                    @else
                        <div class="alert alert-danger mt-3">
                            Le fichier contient des erreurs. Veuillez les corriger et réessayer.
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.electoral-file') }}" class="btn btn-primary">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
