<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - {{ Auth::user()->user_type }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Tableau de bord</a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Bienvenue {{ Auth::user()->name }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Vos informations</h5>
                        <p>Type de compte : {{ ucfirst(Auth::user()->user_type) }}</p>
                        <p>Email : {{ Auth::user()->email }}</p>
                        <p>Téléphone : {{ Auth::user()->phone_number }}</p>
                        
                        @if(Auth::user()->user_type === 'voter')
                            <hr>
                            <h5>Informations de l'électeur</h5>
                            <p>Numéro de carte d'électeur : {{ Auth::user()->voter_card_number }}</p>
                            <p>Numéro de carte d'identité : {{ Auth::user()->national_id_number }}</p>
                            <p>Bureau de vote : {{ Auth::user()->polling_station }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
