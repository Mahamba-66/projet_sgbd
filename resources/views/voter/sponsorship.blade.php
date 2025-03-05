@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Parrainer un candidat') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($candidates->count() > 0)
                        <form method="POST" action="{{ url('/voter/sponsorship') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="candidate_id" class="form-label">{{ __('Sélectionnez un candidat') }}</label>
                                <select id="candidate_id" class="form-select @error('candidate_id') is-invalid @enderror" 
                                    name="candidate_id" required>
                                    <option value="">{{ __('Choisir un candidat') }}</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                                    @endforeach
                                </select>
                                @error('candidate_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="alert alert-warning">
                                    <strong>Important :</strong>
                                    <ul class="mb-0">
                                        <li>Vous ne pouvez parrainer qu'un seul candidat.</li>
                                        <li>Cette action est irréversible.</li>
                                        <li>Votre parrainage sera soumis à validation.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir parrainer ce candidat ? Cette action est irréversible.')">
                                    {{ __('Parrainer ce candidat') }}
                                </button>
                                <a href="{{ url('/voter/dashboard') }}" class="btn btn-secondary">
                                    {{ __('Retour au tableau de bord') }}
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">Aucun candidat n'est disponible pour le parrainage pour le moment.</p>
                        </div>
                        <a href="{{ url('/voter/dashboard') }}" class="btn btn-secondary">
                            {{ __('Retour au tableau de bord') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
