@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Mes Parrainages</h4>
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

                    @if($sponsorships->isEmpty())
                        <div class="text-center py-4">
                            <p class="mb-3">Vous n'avez pas encore parrainé de candidat.</p>
                            <a href="{{ route('candidates.list') }}" class="btn btn-primary">
                                Découvrir les candidats
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Candidat</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Commentaire</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsorships as $sponsorship)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $sponsorship->candidate->photo ? asset('storage/' . $sponsorship->candidate->photo) : asset('images/default-avatar.png') }}" 
                                                         class="rounded-circle me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div>
                                                        <div>{{ $sponsorship->candidate->name }}</div>
                                                        <small class="text-muted">{{ $sponsorship->candidate->party_name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @switch($sponsorship->status)
                                                    @case('valid')
                                                        <span class="badge bg-success">Valide</span>
                                                        @break
                                                    @case('invalid')
                                                        <span class="badge bg-danger">Invalide</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-warning">En attente</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($sponsorship->validation_comment)
                                                    {{ $sponsorship->validation_comment }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sponsorship->status === 'pending')
                                                    <form action="{{ route('voter.sponsorship.cancel', $sponsorship) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler ce parrainage ?')">
                                                            Annuler
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $sponsorships->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
