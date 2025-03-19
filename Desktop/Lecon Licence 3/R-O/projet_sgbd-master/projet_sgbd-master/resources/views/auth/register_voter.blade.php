@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Inscription Électeur</h4>
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

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.voter.submit') }}" id="voterForm">
                        @csrf

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
                            <label for="voter_card_number">Numéro de carte d'électeur</label>
                            <input id="voter_card_number" type="text" 
                                   class="form-control @error('voter_card_number') is-invalid @enderror" 
                                   name="voter_card_number" value="{{ old('voter_card_number') }}" 
                                   required pattern="[A-Z0-9]{10}" maxlength="10">
                            @error('voter_card_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Entrez le numéro de votre carte d'électeur tel qu'il apparaît sur votre carte (10 caractères)
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="region_id" class="form-label">Région</label>
                            <select name="region_id" id="region_id" class="form-control @error('region_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez votre région</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                        {{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-success w-100">
                                Activer mon compte
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
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
    const form = document.getElementById('voterForm');
    
    form.addEventListener('submit', function(e) {
        const cardNumber = document.getElementById('voter_card_number').value;
        const region = document.getElementById('region_id').value;
        
        if (!cardNumber || !region) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires');
        }
    });
});
</script>
@endpush
@endsection
