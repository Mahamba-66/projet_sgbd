@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Importer des parrainages</h2>
                </div>

                <div class="card-body">
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

                    <div class="mb-4">
                        <h4>Instructions</h4>
                        <ol>
                            <li>
                                <a href="{{ route('sponsorship.template') }}" class="btn btn-link p-0">
                                    Téléchargez le modèle de fichier CSV
                                </a>
                            </li>
                            <li>Remplissez le fichier avec les informations des parrains</li>
                            <li>Importez le fichier rempli ci-dessous</li>
                        </ol>
                    </div>

                    <form action="{{ route('sponsorship.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier CSV des parrainages</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".csv">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h5>Format requis :</h5>
                            <ul class="mb-0">
                                <li>Fichier CSV (séparateur: virgule)</li>
                                <li>Taille maximale : 10 Mo</li>
                                <li>Colonnes requises : NIN, Prénom, Nom, Date de naissance, etc.</li>
                            </ul>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Importer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
