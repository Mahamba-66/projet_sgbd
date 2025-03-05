@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Liste des Candidats</div>

                <div class="card-body">
                    <div class="row">
                        @foreach($candidates as $candidate)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($candidate->photo)
                                        <img src="{{ Storage::url($candidate->photo) }}" class="card-img-top" alt="{{ $candidate->name }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $candidate->name }}</h5>
                                        @if($candidate->party_name)
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $candidate->party_name }}</h6>
                                        @endif
                                        <p class="card-text">{{ Str::limit($candidate->biography, 150) }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <form action="{{ route('sponsorship.submit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                            <button type="submit" class="btn btn-primary">Parrainer ce candidat</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
