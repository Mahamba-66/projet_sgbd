@extends('layouts.test')

@section('content')
<div>
    <h1>Tableau de Bord Électeur</h1>
    <p>Bienvenue dans votre espace électeur.</p>
    
    <div>
        <h2>Liste des Candidats</h2>
        <p>Consultez la liste des candidats et leurs programmes.</p>
        <a href="{{ route('voter.candidates.index') }}">Voir les Candidats</a>
    </div>

    <div>
        <h2>Mon Profil</h2>
        <p>Consultez et mettez à jour vos informations.</p>
        <a href="{{ route('voter.profile') }}">Gérer mon Profil</a>
    </div>
</div>
@endsection
