@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de l'activité</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-default">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <p>{{ $activity->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <p>{{ $activity->description }}</p>
                            </div>
                            <div class="form-group">
                                <label>Utilisateur</label>
                                <p>
                                    @if($activity->causer)
                                        {{ $activity->causer->email }}
                                    @else
                                        Système
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type de sujet</label>
                                <p>{{ class_basename($activity->subject_type) }}</p>
                            </div>
                            <div class="form-group">
                                <label>ID du sujet</label>
                                <p>{{ $activity->subject_id }}</p>
                            </div>
                            <div class="form-group">
                                <label>Propriétés</label>
                                <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
