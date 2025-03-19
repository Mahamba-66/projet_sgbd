@extends('layouts.test')

@section('content')
<div>
    <h1>Inscription comme Candidat</h1>

    <form method="POST" action="{{ route('candidate.register') }}" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="name">Nom complet</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="nin">Numéro d'identification nationale</label>
            <input type="text" name="nin" id="nin" value="{{ old('nin') }}" required>
        </div>

        <div>
            <label for="voter_card_number">Numéro de carte d'électeur</label>
            <input type="text" name="voter_card_number" id="voter_card_number" value="{{ old('voter_card_number') }}" required>
        </div>

        <div>
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
        </div>

        <div>
            <label for="region_id">Région</label>
            <select name="region_id" id="region_id" required>
                <option value="">Sélectionnez une région</option>
                @foreach(\App\Models\Region::all() as $region)
                    <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                        {{ $region->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo" accept="image/*">
        </div>

        <div>
            <label for="program">Programme électoral (PDF)</label>
            <input type="file" name="program" id="program" accept=".pdf">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <div>
            <button type="submit">S'inscrire comme candidat</button>
        </div>
    </form>
</div>
@endsection
