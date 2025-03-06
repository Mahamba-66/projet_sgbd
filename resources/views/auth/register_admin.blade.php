@extends('layouts.welcome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Inscription Administrateur</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register.admin.submit') }}">
                        @csrf

                        <!-- Informations personnelles -->
                        <h5 class="mb-3">Informations personnelles</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">Prénom</label>
                                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Nom</label>
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Numéro d'employé</label>
                                    <input id="employee_id" type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                           name="employee_id" value="{{ old('employee_id') }}" required>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Département</label>
                                    <input id="department" type="text" class="form-control @error('department') is-invalid @enderror" 
                                           name="department" value="{{ old('department') }}" required>
                                    @error('department')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations de contact -->
                        <h5 class="mb-3 mt-4">Informations de contact</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Adresse email professionnelle</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Numéro de téléphone professionnel</label>
                                    <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Rôle et permissions -->
                        <h5 class="mb-3 mt-4">Rôle et permissions</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Rôle administratif</label>
                                    <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                                        <option value="">Sélectionnez un rôle</option>
                                        <option value="super_admin">Super Administrateur</option>
                                        <option value="admin">Administrateur</option>
                                        <option value="supervisor">Superviseur</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <h5 class="mb-3 mt-4">Sécurité</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mot de passe</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer le mot de passe</label>
                                    <input id="password_confirmation" type="password" class="form-control" 
                                           name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- Code de vérification administrateur -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admin_code">Code de vérification administrateur</label>
                                    <input id="admin_code" type="text" class="form-control @error('admin_code') is-invalid @enderror" 
                                           name="admin_code" required>
                                    @error('admin_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Ce code vous a été fourni par votre superviseur</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger">
                                    S'inscrire comme administrateur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
