@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Importer des Électeurs</h2>
        <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

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

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.voters.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="file" class="form-label">Fichier CSV</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".csv,.txt">
                    @error('file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="alert alert-info mb-4">
                    <h5 class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Format du fichier CSV attendu
                    </h5>
                    <p>Le fichier doit contenir les colonnes suivantes :</p>
                    <ul class="list-unstyled mb-3">
                        <li><i class="bi bi-check2 me-2"></i><strong>Prenom</strong> : Prénom de l'électeur</li>
                        <li><i class="bi bi-check2 me-2"></i><strong>Nom</strong> : Nom de l'électeur</li>
                        <li><i class="bi bi-check2 me-2"></i><strong>Numero de Carte</strong> : Numéro de la carte d'électeur</li>
                    </ul>
                    <p class="mb-2">Notes importantes :</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-dot me-2"></i>Les noms des colonnes doivent être exactement comme indiqué ci-dessus</li>
                        <li><i class="bi bi-dot me-2"></i>L'ordre des colonnes n'a pas d'importance</li>
                        <li><i class="bi bi-dot me-2"></i>Un email sera automatiquement généré pour chaque électeur</li>
                        <li><i class="bi bi-dot me-2"></i>Le mot de passe par défaut sera : password123</li>
                    </ul>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Importer les électeurs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .alert-info {
        background-color: #f8f9fa;
        border-color: #e3e6f0;
        color: #3a3b45;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .list-unstyled li {
        margin-bottom: 0.5rem;
    }
    
    .bi {
        font-size: 1rem;
    }
</style>
@endsection
