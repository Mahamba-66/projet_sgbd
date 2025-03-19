<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                @auth
                    <ul class="navbar-nav me-auto">
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                                   href="{{ route('admin.users') }}">
                                    <i class="fas fa-users"></i> Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.sponsorships*') ? 'active' : '' }}" 
                                   href="{{ route('admin.sponsorships') }}">
                                    <i class="fas fa-file-signature"></i> Parrainages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.stats') ? 'active' : '' }}" 
                                   href="{{ route('admin.stats') }}">
                                    <i class="fas fa-chart-bar"></i> Statistiques
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isVoter())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('voter.dashboard') }}">
                                    <i class="fas fa-home"></i> Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.candidates*') ? 'active' : '' }}" 
                                   href="{{ route('voter.candidates') }}">
                                    <i class="fas fa-users"></i> Candidats
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.sponsorships*') ? 'active' : '' }}" 
                                   href="{{ route('voter.sponsorships.index') }}">
                                    <i class="fas fa-file-signature"></i> Mes parrainages
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isCandidate())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('candidate.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('candidate.dashboard') }}">
                                    <i class="fas fa-home"></i> Accueil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('candidate.sponsorships') ? 'active' : '' }}" 
                                   href="{{ route('candidate.sponsorships') }}">
                                    <i class="fas fa-file-signature"></i> Parrainages reçus
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('candidate.stats') ? 'active' : '' }}" 
                                   href="{{ route('candidate.stats') }}">
                                    <i class="fas fa-chart-bar"></i> Statistiques
                                </a>
                            </li>
                        @endif
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->isVoter())
                                    <a class="dropdown-item" href="{{ route('voter.profile') }}">
                                        <i class="fas fa-user-edit"></i> Mon profil
                                    </a>
                                @endif
                                
                                @if(auth()->user()->isCandidate())
                                    <a class="dropdown-item" href="{{ route('candidate.profile') }}">
                                        <i class="fas fa-user-edit"></i> Mon profil
                                    </a>
                                @endif
                                
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </button>
                                </form>
                            </ul>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container text-center">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</span>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
