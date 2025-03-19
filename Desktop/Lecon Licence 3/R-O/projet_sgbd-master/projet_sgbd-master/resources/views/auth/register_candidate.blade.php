@extends('layouts.welcome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Inscription Candidat</h4>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.candidate.submit') }}" id="candidateForm">
                        @csrf

                        <!-- Informations personnelles -->
                        <h5 class="mb-3">Informations personnelles</h5>
                        
                        <div class="form-group mb-3">
                            <label for="name">Nom complet</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Adresse email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Mot de passe</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" class="form-control" 
                                   name="password_confirmation" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nin">Numéro d'Identification Nationale (NIN)</label>
                            <input id="nin" type="text" class="form-control @error('nin') is-invalid @enderror" 
                                   name="nin" value="{{ old('nin') }}" required maxlength="13">
                            @error('nin')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Le NIN doit contenir exactement 13 chiffres</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="birth_date">Date de naissance</label>
                            <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   name="birth_date" value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Vous devez avoir au moins 35 ans</small>
                        </div>

                        <!-- Informations du parti -->
                        <h5 class="mb-3 mt-4">Informations du parti</h5>

                        <div class="form-group mb-3">
                            <label for="party_name">Nom du parti</label>
                            <input id="party_name" type="text" class="form-control @error('party_name') is-invalid @enderror" 
                                   name="party_name" value="{{ old('party_name') }}" required>
                            @error('party_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="party_position">Position dans le parti</label>
                            <input id="party_position" type="text" class="form-control @error('party_position') is-invalid @enderror" 
                                   name="party_position" value="{{ old('party_position') }}" required>
                            @error('party_position')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                S'inscrire
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-link text-success">
                                Déjà inscrit ? Connectez-vous
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du NIN
    const ninInput = document.getElementById('nin');
    ninInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
    });

    // Validation de la date de naissance
    const birthDateInput = document.getElementById('birth_date');
    const minDate = new Date();
    minDate.setFullYear(minDate.getFullYear() - 35);
    birthDateInput.max = minDate.toISOString().split('T')[0];
});
</script>
@endpush

<style>
.btn-success {
    background-color: #00843D;
    border-color: #00843D;
}
.btn-success:hover {
    background-color: #006B31;
    border-color: #006B31;
}
.text-success {
    color: #00843D !important;
}
.bg-success {
    background-color: #00843D !important;
}
</style>
@endsection
