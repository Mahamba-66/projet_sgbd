@extends('layouts.simple')

@section('content')
<div class="card">
    <h2>Inscription</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nom complet</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="nin">NIN (13 chiffres)</label>
            <input id="nin" type="text" name="nin" value="{{ old('nin') }}" required>
            @error('nin')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="voter_card_number">Numéro de carte d'électeur</label>
            <input id="voter_card_number" type="text" name="voter_card_number" value="{{ old('voter_card_number') }}" required>
            @error('voter_card_number')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmer le mot de passe</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">S'inscrire</button>
        </div>
    </form>
</div>
@endsection
