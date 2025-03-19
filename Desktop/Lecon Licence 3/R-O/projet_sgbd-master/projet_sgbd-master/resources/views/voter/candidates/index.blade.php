@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Liste des Candidats</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($candidates->isEmpty())
                        <div class="alert alert-info">
                            Aucun candidat validé n'est disponible pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Parti</th>
                                        <th>Position</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidates as $candidate)
                                        <tr>
                                            <td>{{ $candidate->name }}</td>
                                            <td>{{ $candidate->party_name }}</td>
                                            <td>{{ $candidate->party_position }}</td>
                                            <td>
                                                <a href="{{ route('voter.candidates.show', $candidate->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                                
                                                @if($candidate->status === 'validated')
                                                    <a href="{{ route('voter.sponsorships.create', $candidate->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-handshake"></i> Parrainer
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
