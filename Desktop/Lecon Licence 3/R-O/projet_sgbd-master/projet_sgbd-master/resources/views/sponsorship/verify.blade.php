@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vérification du Parrainage</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        Un code de vérification a été envoyé à votre email et numéro de téléphone.
                    </div>

                    <form method="POST" action="{{ route('sponsorship.submit') }}">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ request('candidate_id') }}">

                        <div class="form-group row">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-right">Code de Vérification</label>

                            <div class="col-md-6">
                                <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" required>

                                @error('verification_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Valider le Parrainage
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
