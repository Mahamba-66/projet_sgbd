@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Mes Parrainages</div>

                <div class="card-body">
                    @if($sponsorships->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Candidat</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsorships as $sponsorship)
                                        <tr>
                                            <td>{{ $sponsorship->candidate->name }}</td>
                                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $sponsorship->status === 'pending' ? 'warning' : 'success' }}">
                                                    {{ $sponsorship->status === 'pending' ? 'En attente' : 'Validé' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($sponsorship->status === 'pending')
                                                    <form action="{{ route('voter.sponsorships.cancel', $sponsorship) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce parrainage ?')">
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
                        
                        <div class="mt-3">
                            {{ $sponsorships->links() }}
                        </div>
                    @else
                        <p>Vous n'avez pas encore parrainé de candidat.</p>
                        <a href="{{ route('sponsorship.candidates') }}" class="btn btn-primary">Parrainer un candidat</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
