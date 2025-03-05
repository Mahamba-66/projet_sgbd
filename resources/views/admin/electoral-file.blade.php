@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Import du Fichier Électoral</h4>
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

                    <form action="{{ route('admin.electoral-file.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier CSV des électeurs</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".csv" required>
                            <div class="form-text">Le fichier doit être au format CSV et encodé en UTF-8.</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="checksum" class="form-label">Empreinte SHA256</label>
                            <input type="text" class="form-control @error('checksum') is-invalid @enderror" id="checksum" name="checksum" required>
                            <div class="form-text">Entrez l'empreinte SHA256 du fichier pour la vérification.</div>
                            @error('checksum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Importer le fichier
                        </button>
                    </form>

                    @if($lastUpload ?? null)
                        <hr>
                        <h5>Dernier import</h5>
                        <p>Status : 
                            <span class="badge bg-{{ $lastUpload->status === 'validated' ? 'success' : ($lastUpload->status === 'error' ? 'danger' : 'warning') }}">
                                {{ $lastUpload->status }}
                            </span>
                        </p>
                        <p>Date : {{ $lastUpload->created_at->format('d/m/Y H:i') }}</p>
                        @if($lastUpload->validation_errors)
                            <div class="alert alert-danger">
                                <h6>Erreurs de validation :</h6>
                                <ul>
                                    @foreach($lastUpload->validation_errors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
