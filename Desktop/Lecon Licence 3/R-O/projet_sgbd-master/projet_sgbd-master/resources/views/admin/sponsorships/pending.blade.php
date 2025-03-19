@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Parrainages en attente') }}</div>

                <div class="card-body">
                    @if($sponsorships->isEmpty())
                        <p class="text-center">Aucun parrainage en attente.</p>
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
                                            <td>{{ $sponsorship->voter->name }}</td>
                                            <td>{{ $sponsorship->candidate->name }}</td>
                                            <td>{{ $sponsorship->voter->region->name }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.sponsorships.validate', $sponsorship) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.sponsorships.reject', $sponsorship) }}" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
                                                </form>
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
