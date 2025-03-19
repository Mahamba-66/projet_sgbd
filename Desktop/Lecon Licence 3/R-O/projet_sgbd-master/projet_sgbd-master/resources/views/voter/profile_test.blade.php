@extends('layouts.test')

@section('content')
<div>
    <h1>Mon Profil</h1>
    
    <form method="POST" action="{{ route('voter.profile.update') }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required>
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
