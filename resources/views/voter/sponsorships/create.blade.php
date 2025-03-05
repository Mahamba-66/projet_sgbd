@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Parrainer un Candidat</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="candidate-info mb-4">
                        <h5>Informations du Candidat</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nom :</strong> {{ $candidate->name }}</p>
                                <p><strong>Parti :</strong> {{ $candidate->party_name }}</p>
                                <p><strong>Position :</strong> {{ $candidate->party_position }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h5 class="alert-heading">Important !</h5>
                        <p>En parrainant ce candidat :</p>
                        <ul>
                            <li>Vous ne pourrez plus parrainer un autre candidat</li>
                            <li>Votre parrainage sera soumis à validation</li>
                            <li>Une fois validé, il ne pourra pas être retiré</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('voter.sponsorships.store', $candidate->id) }}" class="mt-4">
                        @csrf

                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm" id="confirm" required>
                                <label class="form-check-label" for="confirm">
                                    Je confirme vouloir parrainer ce candidat
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-handshake"></i> Confirmer le Parrainage
                            </button>
                            <a href="{{ route('voter.candidates.index') }}" class="btn btn-light">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
