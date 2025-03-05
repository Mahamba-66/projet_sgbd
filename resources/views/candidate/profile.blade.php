@extends('layouts.welcome')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Mon Profil</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('candidate.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Photo de profil -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                @if($user->photo)
                                    <img src="{{ Storage::url($user->photo) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle mb-3"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-3"
                                         style="width: 150px; height: 150px;">
                                        <span class="display-4 text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <label for="photo" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                                    <i class="fas fa-camera text-primary"></i>
                                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <!-- Informations du parti -->
                        <div class="mb-4">
                            <h5 class="card-title">Informations du parti</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="party_name" class="form-label">Nom du parti</label>
                                    <input type="text" name="party_name" id="party_name" 
                                           value="{{ old('party_name', $user->party_name) }}"
                                           class="form-control @error('party_name') is-invalid @enderror">
                                    @error('party_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="party_position" class="form-label">Position dans le parti</label>
                                    <input type="text" name="party_position" id="party_position" 
                                           value="{{ old('party_position', $user->party_position) }}"
                                           class="form-control @error('party_position') is-invalid @enderror">
                                    @error('party_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations personnelles -->
                        <div class="mb-4">
                            <h5 class="card-title">Informations personnelles</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" name="phone" id="phone" 
                                           value="{{ old('phone', $user->phone) }}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           pattern="[0-9]{9}"
                                           placeholder="77XXXXXXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nin" class="form-label">NIN</label>
                                    <input type="text" id="nin" 
                                           value="{{ $user->nin }}"
                                           class="form-control"
                                           disabled
                                           readonly>
                                    <small class="text-muted">Le NIN ne peut pas être modifié</small>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <h5 class="card-title">Biographie</h5>
                            <div class="form-group">
                                <textarea name="bio" id="bio" rows="4" 
                                          class="form-control @error('bio') is-invalid @enderror"
                                          placeholder="Parlez-nous de vous et de votre vision pour le Sénégal...">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('photo').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.rounded-circle');
            if (img.tagName === 'IMG') {
                img.src = e.target.result;
            } else {
                const parent = img.parentElement;
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.alt = "Photo de profil";
                newImg.className = "rounded-circle mb-3";
                newImg.style.width = "150px";
                newImg.style.height = "150px";
                newImg.style.objectFit = "cover";
                parent.replaceChild(newImg, img);
            }
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
@endsection
