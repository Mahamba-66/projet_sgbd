@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Paramètres du Système</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations Système
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Version du Système</th>
                            <td>{{ $settings['system_version'] }}</td>
                        </tr>
                        <tr>
                            <th>Version PHP</th>
                            <td>{{ $settings['php_version'] }}</td>
                        </tr>
                        <tr>
                            <th>Version Laravel</th>
                            <td>{{ $settings['laravel_version'] }}</td>
                        </tr>
                        <tr>
                            <th>Nombre Total d'Utilisateurs</th>
                            <td>{{ $settings['total_users'] }}</td>
                        </tr>
                        <tr>
                            <th>Administrateurs</th>
                            <td>{{ $settings['admin_users'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-key me-1"></i>
                    Changer le Mot de Passe
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.update-password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de Passe Actuel</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau Mot de Passe</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirmer le Nouveau Mot de Passe</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Mettre à jour le Mot de Passe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
