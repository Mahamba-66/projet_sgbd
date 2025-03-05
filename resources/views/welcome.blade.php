<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Système de Parrainage - Sénégal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,132,61,0.7)), url('/images/palais.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            padding: 15px 30px;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-custom-primary {
            background-color: #00843D;
            color: white;
            border: none;
        }

        .btn-custom-primary:hover {
            background-color: #006B31;
            color: white;
            transform: translateY(-3px);
        }

        .btn-custom-outline {
            border: 2px solid white;
            color: white;
        }

        .btn-custom-outline:hover {
            background-color: white;
            color: #00843D;
            transform: translateY(-3px);
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-circle i {
            font-size: 30px;
            color: #00843D;
        }

        .stats-card {
            background: linear-gradient(45deg, #00843D, #006B31);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            height: 100%;
        }

        .stats-number {
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .nav-link {
            color: white;
            margin: 0 15px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg position-absolute w-100" style="z-index: 1000;">
        <div class="container">
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="nav-link d-inline-block">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </a>
                <a href="{{ route('register') }}" class="nav-link d-inline-block">
                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                </a>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="glass-card">
                        <h1 class="display-4 mb-4">Système de<br>Parrainage Électoral<br>du Sénégal</h1>
                        <p class="lead mb-5">Participez au processus démocratique en toute transparence. Votre voix compte pour l'avenir du Sénégal.</p>
                        <div class="d-grid gap-3 d-sm-flex">
                            <a href="{{ route('register') }}" class="btn btn-custom btn-custom-primary">S'inscrire en tant que candidat</a>
                            <a href="{{ route('register') }}" class="btn btn-custom btn-outline-light">S'inscrire en tant qu'électeur</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Un processus simple en trois étapes</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="h4 mb-3">Inscription</h3>
                        <p class="text-muted">Créez votre compte en tant qu'électeur ou candidat</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-circle">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="h4 mb-3">Vérification</h3>
                        <p class="text-muted">Validation de votre identité</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-circle">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="h4 mb-3">Parrainage</h3>
                        <p class="text-muted">Soutenez votre candidat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">14</div>
                        <div>Régions</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">44,231</div>
                        <div>Parrainages requis</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">7</div>
                        <div>Jours restants</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h4>À Propos</h4>
                    <p class="text-muted">Plateforme officielle de parrainage électoral de la République du Sénégal</p>
                </div>
                <div class="col-md-4">
                    <h4>Contact</h4>
                    <p class="text-muted">Email: contact@parrainage.gouv.sn<br>Tél: +221 XX XXX XX XX</p>
                </div>
                <div class="col-md-4">
                    <h4>Liens Utiles</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <p class="text-center text-muted mb-0"> 2024 Système de Parrainage Électoral du Sénégal. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
