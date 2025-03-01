<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Système de Parrainage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Inscription</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="mb-3">
                                <label for="role" class="form-label">Type d'utilisateur</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required onchange="toggleFields()">
                                    <option value="">Sélectionnez un type</option>
                                    <option value="voter">Électeur</option>
                                    <option value="candidate">Candidat</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="voter-fields">
                                <div class="mb-3">
                                    <label for="voter_card_number" class="form-label">Numéro de Carte d'Électeur</label>
                                    <input type="text" class="form-control @error('voter_card_number') is-invalid @enderror" 
                                        id="voter_card_number" name="voter_card_number" value="{{ old('voter_card_number') }}">
                                    @error('voter_card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nin" class="form-label">Numéro d'Identification National (NIN)</label>
                                    <input type="text" class="form-control @error('nin') is-invalid @enderror" 
                                        id="nin" name="nin" value="{{ old('nin') }}">
                                    @error('nin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="region" class="form-label">Région</label>
                                    <input type="text" class="form-control @error('region') is-invalid @enderror" 
                                        id="region" name="region" value="{{ old('region') }}">
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="department" class="form-label">Département</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                        id="department" name="department" value="{{ old('department') }}">
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="commune" class="form-label">Commune</label>
                                    <input type="text" class="form-control @error('commune') is-invalid @enderror" 
                                        id="commune" name="commune" value="{{ old('commune') }}">
                                    @error('commune')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="polling_station" class="form-label">Bureau de Vote</label>
                                    <input type="text" class="form-control @error('polling_station') is-invalid @enderror" 
                                        id="polling_station" name="polling_station" value="{{ old('polling_station') }}">
                                    @error('polling_station')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">S'inscrire</button>
                                <a href="{{ route('login') }}" class="btn btn-link">Déjà inscrit ?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            const voterFields = document.getElementById('voter-fields');
            const fields = voterFields.querySelectorAll('input');
            
            if (role === 'voter' || role === 'candidate') {
                voterFields.style.display = 'block';
                fields.forEach(field => field.required = true);
            } else {
                voterFields.style.display = 'none';
                fields.forEach(field => {
                    field.required = false;
                    field.value = '';
                });
            }
        }

        // Appeler la fonction au chargement de la page
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</body>
</html>
