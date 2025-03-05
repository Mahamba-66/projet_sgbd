<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration - Système Electoral Sénégal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar-link {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar-link:hover {
            background-color: #454d55;
            color: #ffffff;
        }
        .sidebar-link.active {
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="py-4 px-3 mb-4 bg-dark">
                    <div class="text-white">
                        <h5>{{ auth()->user()->name }}</h5>
                        <p class="mb-0">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Tableau de Bord
                        </a>
                    </li>

                    @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.candidates.index') }}" class="sidebar-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Candidats
                        </a>
                    </li>

                    <li class="nav-item">
                        <div class="sb-sidenav-menu-heading">Gestion des Électeurs</div>
                        <a class="nav-link" href="{{ route('admin.voters.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Liste des Électeurs
                        </a>
                        <a class="nav-link" href="{{ route('admin.voters.eligible') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Électeurs Éligibles
                        </a>
                        <a class="nav-link" href="{{ route('admin.voters.import') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-import"></i></div>
                            Importer Liste Électeurs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.sponsorships.index') }}" class="sidebar-link {{ request()->routeIs('admin.sponsorships.*') ? 'active' : '' }}">
                            <i class="fas fa-file-signature me-2"></i> Parrainages
                        </a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('admin.statistics') }}" class="sidebar-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i> Statistiques
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="fas fa-file-alt me-2"></i> Rapports
                        </a>
                    </li>

                    @if(auth()->user()->role === 'super_admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="fas fa-user-cog me-2"></i> Gestion des Utilisateurs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="fas fa-cogs me-2"></i> Paramètres
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                            <i class="fas fa-history me-2"></i> Journaux d'Audit
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top navbar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
