<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Panel - Système de Gestion des Parrainages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            padding: 1rem;
            display: block;
        }
        .sidebar a:hover {
            color: rgba(255,255,255,1);
            background-color: rgba(255,255,255,.1);
        }
        .sidebar a.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="py-4 px-3 mb-4 text-white">
                    <h5><i class="fas fa-user-shield"></i> Admin Panel</h5>
                </div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                    <a href="{{ route('admin.candidates.index') }}" class="{{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i> Candidats
                    </a>
                    <a href="{{ route('admin.voters.list') }}" class="{{ request()->routeIs('admin.voters.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Électeurs
                    </a>
                    <a href="{{ route('admin.sponsorships.index') }}" class="{{ request()->routeIs('admin.sponsorships.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i> Parrainages
                    </a>
                    <a href="{{ route('admin.sponsorship-periods.index') }}" class="{{ request()->routeIs('admin.sponsorship-periods.*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> Périodes de Parrainage
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 content">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <span class="text-muted">Admin</span>
                        <span class="mx-2">-</span>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>