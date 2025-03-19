@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">{{ __('Détails du Candidat') }}</h4>
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

                    <div class="row">
                        <div class="col-md-12">
                            <h5>{{ $candidate->name }}</h5>
                            <p class="text-muted mb-2">Candidat à l'élection présidentielle</p>
                            
                            <div class="candidate-info">
                                <p><strong>Parti :</strong> {{ $candidate->party_name }}</p>
                                <p><strong>Position :</strong> {{ $candidate->party_position }}</p>
                                <p><strong>Statut :</strong> 
                                    @if($candidate->status === 'validated')
                                        <span class="badge bg-success">Validé</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </p>
                                @if($candidate->validated_at)
                                    <p><strong>Date de validation :</strong> {{ \Carbon\Carbon::parse($candidate->validated_at)->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>

                            <div class="mt-4">
                                @if($candidate->status === 'validated')
                                    <a href="{{ route('voter.sponsorships.create', $candidate->id) }}" class="btn btn-success">
                                        <i class="fas fa-handshake"></i> Parrainer ce candidat
                                    </a>
                                @endif
                                <a href="{{ route('voter.candidates.index') }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Programme du candidat</h5>
                            <div class="program-content">
                                @if($candidate->program)
                                    {!! nl2br(e($candidate->program)) !!}
                                @else
                                    <p class="text-muted">Aucun programme n'a été soumis pour le moment.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-image {
    text-align: center;
    margin-bottom: 1rem;
}

.no-image {
    width: 150px;
    height: 150px;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #6c757d;
}

.candidate-info {
    margin: 1rem 0;
}

.program-content {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    min-height: 200px;
}
</style>
@endsection
