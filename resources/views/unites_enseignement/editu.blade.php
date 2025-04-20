@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white text-center">
            <h3>✏ Modifier l'Unité d'Enseignement</h3>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (isset($ue))
                <form action="{{ route('unites-enseignement.update', $ue->id_ue) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-book"></i> Intitulé</label>
                            <input type="text" name="intitule" class="form-control" value="{{ old('intitule', $ue->intitule) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-tags"></i> Type</label>
                            <input type="text" name="type" class="form-control" value="{{ old('type', $ue->type) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-mortarboard"></i> Niveau Scolaire</label>
                            <input type="text" name="niveau_scolaire" class="form-control" value="{{ old('niveau_scolaire', $ue->niveau_scolaire) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-hash"></i> Code</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $ue->code) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-file-text"></i> Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $ue->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-calendar"></i> Année Universitaire</label>
                            <input type="text" name="annee_universitaire" class="form-control" value="{{ old('annee_universitaire', $ue->annee_universitaire) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success px-4">✅ Mettre à jour</button>
                        <a href="{{ route('unites-enseignement.index') }}" class="btn btn-danger px-4">❌ Annuler</a>
                    </div>
                </form>
            @else
                <p class="alert alert-warning text-center mt-3">⚠ Aucune donnée trouvée pour cette Unité d'Enseignement.</p>
            @endif
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .btn-success, .btn-danger {
        border-radius: 8px;
        font-weight: bold;
    }

    .form-label {
        font-size: 1rem;
    }
</style>

@endsection
