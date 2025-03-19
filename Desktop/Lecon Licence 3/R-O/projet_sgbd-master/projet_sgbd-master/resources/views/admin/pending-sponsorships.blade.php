@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Parrainages en attente') }}</div>

                <div class="card-body">
                    @if($sponsorships->isEmpty())
                        <div class="alert alert-info">
                            Aucun parrainage en attente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Électeur</th>
                                        <th>Candidat</th>
                                        <th>Région</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sponsorships as $sponsorship)
                                        <tr>
                                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                {{ $sponsorship->voter->name }}<br>
                                                <small class="text-muted">{{ $sponsorship->voter->nin }}</small>
                                            </td>
                                            <td>
                                                {{ $sponsorship->candidate->name }}<br>
                                                <small class="text-muted">{{ $sponsorship->candidate->party_name }}</small>
                                            </td>
                                            <td>{{ $sponsorship->region->name }}</td>
                                            <td>
                                                <form action="{{ route('admin.sponsorship.validate', $sponsorship->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" name="action" value="approve">
                                                        Approuver
                                                    </button>
                                                    <button type="submit" class="btn btn-danger btn-sm" name="action" value="reject">
                                                        Rejeter
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $sponsorships->links() }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Retour au tableau de bord</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
