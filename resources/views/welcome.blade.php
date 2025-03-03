<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page avec En-tête Structuré</title>
    <!-- Utilisation de asset() pour le CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<header>
    <div class="logo-container">
        <!-- Utilisation de asset() pour les images -->
        <img src="{{ asset('images/logo.jpg') }}" alt="Drapeau du Sénégal" class="logo">
        <span class="slogan">Un Peuple - Un But - Une Foi</span>
    </div>

    <nav class="navbar">
        <a href="#accueil">Accueil</a>
        <a href="#savoir-plus">A propos</a>
        <a href="#contact">Contact</a>
    </nav>

    <div class="auth-links">
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}">Inscription</a>
    </div>
</header>

<main>

    <!-- Section Parrainage -->
    <section id="accueil" class="section parrainage">
        <div class="parrainage-content">
            <div class="text">
                <h1>Parrainage en ligne</h1>
                <p>Votre plateforme de parrainage pour exercer votre droit démocratique, et soutenir le candidat de votre choix lors des prochaines élections.</p>
                <a href="#savoir-plus" class="btn">En savoir plus</a>
            </div>
            <div class="image">
                <img src="{{ asset('images/parrainages.jpg') }}" alt="Image Parrainage">
            </div>
        </div>
    </section>

    <!-- Section À Propos -->
    <section id="savoir-plus" class="section">
        <div class="about">
            <img src="{{ asset('images/about.jpg') }}" alt="Image About">
            <div class="about-text">
                <h1>À propos du site</h1>
                <p>Dans le cadre de la numérisation des élections présidentielles au Sénégal...</p>
                <a href="#contact" class="btn">Contactez-nous</a>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="section">
        <h2>Contactez-nous</h2>
        <p>Email : exemple@email.com</p>
        <p>Téléphone : +33 6 12 34 56 78</p>
    </section>

</main>

<a href="#accueil" class="top-button">Top</a>

<footer>
    <p>&copy; 2025 - Direction Générale des Elections - Tous droits réservés</p>
</footer>

</body>
</html>
