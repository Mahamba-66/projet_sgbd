@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0">Nouvelle Période de Parrainage</h2>
        <a href="{{ route('admin.sponsorship-periods.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.sponsorship-periods.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date" class="form-label fw-bold">Date de début</label>
                            <input type="datetime-local" 
                                   class="form-control form-control-lg @error('start_date') is-invalid @enderror" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: JJ/MM/AAAA HH:MM</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date" class="form-label fw-bold">Date de fin</label>
                            <input type="datetime-local" 
                                   class="form-control form-control-lg @error('end_date') is-invalid @enderror" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: JJ/MM/AAAA HH:MM</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="min_sponsorships" class="form-label fw-bold">Nombre minimum de parrainages</label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('min_sponsorships') is-invalid @enderror" 
                                   id="min_sponsorships" 
                                   name="min_sponsorships" 
                                   value="{{ old('min_sponsorships', 1) }}" 
                                   min="1" 
                                   required>
                            @error('min_sponsorships')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_sponsorships" class="form-label fw-bold">Nombre maximum de parrainages</label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('max_sponsorships') is-invalid @enderror" 
                                   id="max_sponsorships" 
                                   name="max_sponsorships" 
                                   value="{{ old('max_sponsorships', 10) }}" 
                                   min="1" 
                                   required>
                            @error('max_sponsorships')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-save me-2"></i> Enregistrer
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
        padding: 2rem;
    }
    
    .form-control {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
        border: 2px solid #d1d3e2;
        height: calc(3rem + 2px);
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .form-label {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        color: #4e4e4e;
    }
    
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1rem;
    }
    
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
    
    .form-text {
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: #858796;
    }
    
    .invalid-feedback {
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .row {
        margin-left: -1rem;
        margin-right: -1rem;
    }
    
    .col-md-6 {
        padding: 1rem;
    }

    input[type="datetime-local"] {
        min-height: calc(3rem + 2px);
    }

    /* Style pour les placeholders */
    ::placeholder {
        color: #b7b9cc;
        opacity: 1;
    }

    :-ms-input-placeholder {
        color: #b7b9cc;
    }

    ::-ms-input-placeholder {
        color: #b7b9cc;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les valeurs minimales des dates
    const now = new Date();
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    // Formater la date pour datetime-local
    const formatDate = (date) => {
        return date.toISOString().slice(0, 16);
    };
    
    // Définir la date minimale comme aujourd'hui
    startDateInput.min = formatDate(now);
    
    // Mettre à jour la date minimale de fin quand la date de début change
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        endDateInput.min = formatDate(startDate);
        
        // Si la date de fin est avant la date de début, la mettre à jour
        if (endDateInput.value && new Date(endDateInput.value) < startDate) {
            endDateInput.value = formatDate(startDate);
        }
    });
    
    // Validation des nombres de parrainages
    const minSponsorship = document.getElementById('min_sponsorships');
    const maxSponsorship = document.getElementById('max_sponsorships');
    
    minSponsorship.addEventListener('change', function() {
        maxSponsorship.min = this.value;
        if (parseInt(maxSponsorship.value) < parseInt(this.value)) {
            maxSponsorship.value = this.value;
        }
    });
    
    maxSponsorship.addEventListener('change', function() {
        if (parseInt(this.value) < parseInt(minSponsorship.value)) {
            this.value = minSponsorship.value;
        }
    });
});
</script>
@endpush

@endsection
