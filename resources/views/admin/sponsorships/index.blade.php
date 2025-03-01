@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Parrainages</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Électeur</th>
                            <th>Candidat</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sponsorships as $sponsorship)
                        <tr>
                            <td>{{ $sponsorship->voter->name }}</td>
                            <td>{{ $sponsorship->candidate->name }}</td>
                            <td>
                                {{ $sponsorship->period->start_date->format('d/m/Y') }} - 
                                {{ $sponsorship->period->end_date->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $sponsorship->status === 'validated' ? 'success' : ($sponsorship->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($sponsorship->status) }}
                                </span>
                            </td>
                            <td>{{ $sponsorship->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($sponsorship->status === 'pending')
                                <form action="{{ route('admin.sponsorships.validate', $sponsorship) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.sponsorships.reject', $sponsorship) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sponsorships->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
