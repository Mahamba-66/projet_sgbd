@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Importer la Liste des Électeurs</h1>

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

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-import me-1"></i>
            Importation du Fichier Excel
        </div>
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ asset('modele_electeurs.xlsx') }}" class="btn btn-info">
                    <i class="fas fa-download me-1"></i> Télécharger le Modèle Excel
                </a>
            </div>

            <form action="{{ route('admin.voters.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Fichier Excel (xlsx, xls)</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                           id="file" name="file" accept=".xlsx,.xls">
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info mb-4">
                    <h5><i class="fas fa-info-circle me-1"></i> Instructions importantes :</h5>
                    <ol class="mb-0">
                        <li>Utilisez le modèle Excel fourni ci-dessus</li>
                        <li>Ne modifiez pas le nom des colonnes (prenom, nom, numero_de_carte)</li>
                        <li>Remplissez toutes les colonnes pour chaque électeur</li>
                        <li>Les numéros de carte doivent être uniques</li>
                    </ol>
                </div>

                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-upload me-1"></i> Importer
                </button>
                
                <a href="{{ route('admin.voters.eligible') }}" class="btn btn-secondary">
                    <i class="fas fa-list me-1"></i> Voir la Liste des Électeurs
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
