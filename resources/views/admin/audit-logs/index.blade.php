@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Journal d'activités</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Utilisateur</th>
                                    <th>Sujet</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>
                                        @if($activity->causer)
                                            {{ $activity->causer->email }}
                                        @else
                                            Système
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->subject)
                                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.audit-logs.show', $activity->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Détails
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
