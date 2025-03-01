@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Mon Profil</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('voter.update-profile') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Photo de profil -->
                        <div class="mb-4 text-center">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="Photo de profil">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                            @endif
                            <div class="mt-3">
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Informations électorales -->
                        <div class="mb-3">
                            <label for="region" class="form-label">Région</label>
                            <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $user->region) }}">
                            @error('region')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Département</label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $user->department) }}">
                            @error('department')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="commune" class="form-label">Commune</label>
                            <input type="text" class="form-control @error('commune') is-invalid @enderror" id="commune" name="commune" value="{{ old('commune', $user->commune) }}">
                            @error('commune')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="polling_station" class="form-label">Bureau de vote</label>
                            <input type="text" class="form-control @error('polling_station') is-invalid @enderror" id="polling_station" name="polling_station" value="{{ old('polling_station', $user->polling_station) }}">
                            @error('polling_station')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour mon profil
                            </button>
                            <a href="{{ route('voter.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
