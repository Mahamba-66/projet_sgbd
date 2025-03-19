@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Vérification du compte</h4>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p>Un code de vérification a été envoyé à votre adresse email.</p>
                    <p>Veuillez saisir ce code pour activer votre compte.</p>

                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="verification_code" class="col-md-4 col-form-label text-md-end">
                                Code de vérification
                            </label>

                            <div class="col-md-6">
                                <input id="verification_code" type="text" 
                                       class="form-control @error('verification_code') is-invalid @enderror" 
                                       name="verification_code" value="{{ old('verification_code') }}" 
                                       required maxlength="6">

                                @error('verification_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Vérifier
                                </button>

                                <a href="{{ route('verification.resend') }}" class="btn btn-link">
                                    Renvoyer le code
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
