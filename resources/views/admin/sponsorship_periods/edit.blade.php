@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Modifier la Période de Parrainage</h2>
        <a href="{{ route('admin.sponsorship-periods.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.sponsorship-periods.update', $sponsorshipPeriod) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Date de début</label>
                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" 
                               value="{{ old('start_date', $sponsorshipPeriod->start_date->format('Y-m-d\TH:i')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Date de fin</label>
                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" 
                               value="{{ old('end_date', $sponsorshipPeriod->end_date->format('Y-m-d\TH:i')) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="min_sponsorships" class="form-label">Nombre minimum de parrainages</label>
                        <input type="number" class="form-control @error('min_sponsorships') is-invalid @enderror" 
                               id="min_sponsorships" name="min_sponsorships" 
                               value="{{ old('min_sponsorships', $sponsorshipPeriod->min_sponsorships) }}" min="1" required>
                        @error('min_sponsorships')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="max_sponsorships" class="form-label">Nombre maximum de parrainages</label>
                        <input type="number" class="form-control @error('max_sponsorships') is-invalid @enderror" 
                               id="max_sponsorships" name="max_sponsorships" 
                               value="{{ old('max_sponsorships', $sponsorshipPeriod->max_sponsorships) }}" min="1" required>
                        @error('max_sponsorships')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
</style>
@endsection
