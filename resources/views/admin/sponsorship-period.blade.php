<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Période de Parrainage - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    @include('admin.partials.navbar')

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Gestion de la Période de Parrainage</h4>
                    </div>
                    <div class="card-body">
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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.sponsorship-period.save') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Date de début</label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                    id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="end_date" class="form-label">Date de fin</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                    id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="min_sponsorships" class="form-label">Nombre minimum de parrainages requis</label>
                                <input type="number" class="form-control @error('min_sponsorships') is-invalid @enderror" 
                                    id="min_sponsorships" name="min_sponsorships" value="{{ old('min_sponsorships') }}" required min="1">
                                @error('min_sponsorships')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="max_sponsorships" class="form-label">Nombre maximum de parrainages autorisés</label>
                                <input type="number" class="form-control @error('max_sponsorships') is-invalid @enderror" 
                                    id="max_sponsorships" name="max_sponsorships" value="{{ old('max_sponsorships') }}" required min="1">
                                @error('max_sponsorships')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer la période
                            </button>
                        </form>

                        @if($currentPeriod ?? null)
                            <hr>
                            <h5>Période actuelle</h5>
                            <table class="table">
                                <tr>
                                    <th>Début</th>
                                    <td>{{ $currentPeriod->start_date->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Fin</th>
                                    <td>{{ $currentPeriod->end_date->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Min. parrainages</th>
                                    <td>{{ $currentPeriod->min_sponsorships }}</td>
                                </tr>
                                <tr>
                                    <th>Max. parrainages</th>
                                    <td>{{ $currentPeriod->max_sponsorships }}</td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        @if($currentPeriod->isActive())
                                            <span class="badge bg-success">Active</span>
                                        @elseif($currentPeriod->isPending())
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-danger">Terminée</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
