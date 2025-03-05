@extends('layouts.welcome')

@section('content')
<div class="container py-4">
    <!-- En-tête -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                @if($user->photo)
                    <img src="{{ Storage::url($user->photo) }}" 
                         alt="{{ $user->name }}" 
                         class="rounded-circle me-4"
                         style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-4"
                         style="width: 100px; height: 100px;">
                        <span class="display-4 text-white">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h2 class="mb-1">{{ $user->name }}</h2>
                    <p class="text-muted mb-2">{{ $user->party_name }} - {{ $user->party_position }}</p>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success me-2">Candidat</span>
                        <span class="text-muted">• Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Parrainages</h5>
                    <p class="display-4">{{ $sponsorshipsCount }}</p>
                    <p class="text-muted">sur 44 231 requis</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Progression</h5>
                    @php
                        $progress = ($sponsorshipsCount / 44231) * 100;
                        $progress = min($progress, 100);
                    @endphp
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: {{ $progress }}%"
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($progress, 1) }}%
                        </div>
                    </div>
                    <p class="text-muted">Objectif : 100%</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statut</h5>
                    @if($sponsorshipsCount >= 44231)
                        <div class="text-success">
                            <i class="fas fa-check-circle"></i>
                            <span>Éligible</span>
                        </div>
                    @else
                        <div class="text-warning">
                            <i class="fas fa-clock"></i>
                            <span>En cours</span>
                        </div>
                    @endif
                    <p class="text-muted">Mise à jour en temps réel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Actions rapides</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('candidate.sponsorships') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-list me-2"></i>
                        Voir mes parrainages
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('candidate.profile') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier mon profil
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('candidate.report.download') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-download me-2"></i>
                        Télécharger le rapport
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.progress {
    border-radius: 1rem;
}

.progress-bar {
    border-radius: 1rem;
}
</style>
@endpush
