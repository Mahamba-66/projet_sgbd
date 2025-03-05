<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Système de Parrainage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #343a40;
            --sidebar-hover: #2c3034;
            --sidebar-active: #0d6efd;
            --sidebar-text: #adb5bd;
            --sidebar-active-text: #fff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.2);
        }

        .sidebar-header h4 {
            color: var(--sidebar-active-text);
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.2rem 0;
        }
        
        .nav-link {
            color: var(--sidebar-text) !important;
            padding: 0.8rem 1.5rem !important;
            display: flex !important;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            color: var(--sidebar-active-text) !important;
            background-color: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }
        
        .nav-link.active {
            color: var(--sidebar-active-text) !important;
            background-color: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        .logout-section {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background-color: rgba(0,0,0,0.2);
        }

        .btn-logout {
            width: 100%;
            color: #dc3545;
            background: transparent;
            border: 1px solid #dc3545;
            padding: 0.5rem;
            border-radius: 4px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .btn-logout i {
            margin-right: 8px;
        }

        .btn-logout:hover {
            color: #fff;
            background: #dc3545;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            color: #212529;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Admin Panel</h4>
        </div>
        
        <div class="sidebar-content">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>Tableau de bord
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/candidates*') ? 'active' : '' }}" href="{{ route('admin.candidates.index') }}">
                        <i class="bi bi-person"></i>Candidats
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/voters*') ? 'active' : '' }}" href="{{ route('admin.voters.index') }}">
                        <i class="bi bi-people"></i>Électeurs
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/sponsorship-periods*') ? 'active' : '' }}" href="{{ route('admin.sponsorship-periods.index') }}">
                        <i class="bi bi-calendar-range"></i>Périodes de Parrainage
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/sponsorships*') ? 'active' : '' }}" href="{{ route('admin.sponsorships.index') }}">
                        <i class="bi bi-file-text"></i>Parrainages
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/statistics*') ? 'active' : '' }}" href="{{ route('admin.statistics') }}">
                        <i class="bi bi-graph-up"></i>Statistiques
                    </a>
                </li>
            </ul>
        </div>

        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>Déconnexion
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>