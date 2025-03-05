@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($candidate) ? 'Modifier le Candidat' : 'Ajouter un Candidat' }}</h5>
                    <a href="{{ route('admin.candidates.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($candidate) ? route('admin.candidates.update', $candidate) : route('admin.candidates.store') }}" 
                          method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        @if(isset($candidate))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du Candidat</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $candidate->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="party_name" class="form-label">Nom du Parti</label>
                            <input type="text" class="form-control @error('party_name') is-invalid @enderror" 
                                   id="party_name" name="party_name" value="{{ old('party_name', $candidate->party_name ?? '') }}" required>
                            @error('party_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biographie</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="3" required>{{ old('bio', $candidate->bio ?? '') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($candidate) && $candidate->photo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->name }}" class="h-20 w-20 object-cover rounded">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="program" class="form-label">Programme Electoral (PDF)</label>
                            <input type="file" class="form-control @error('program') is-invalid @enderror" 
                                   id="program" name="program" accept=".pdf">
                            @error('program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(isset($candidate) && $candidate->program)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($candidate->program) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        Voir le programme actuel
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ isset($candidate) ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Validation côté client Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush
