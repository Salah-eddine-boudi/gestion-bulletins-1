@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3><i class="bi bi-plus-circle"></i> Ajouter une Unité d'Enseignement</h3>
        </div>
        <div class="card-body">

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('unites-enseignement.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-book"></i> Intitulé</label>
                        <input type="text" name="intitule" class="form-control @error('intitule') is-invalid @enderror" value="{{ old('intitule') }}" required>
                        @error('intitule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-tags"></i> Type</label>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-mortarboard"></i> Niveau Scolaire</label>
                        <input type="text" name="niveau_scolaire" class="form-control @error('niveau_scolaire') is-invalid @enderror" value="{{ old('niveau_scolaire') }}" required>
                        @error('niveau_scolaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-hash"></i> Code</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-file-text"></i> Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold"><i class="bi bi-calendar"></i> Année Universitaire</label>
                        <input type="text" name="annee_universitaire" class="form-control @error('annee_universitaire') is-invalid @enderror" value="{{ old('annee_universitaire') }}" required>
                        @error('annee_universitaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-circle"></i> Enregistrer</button>
                    <a href="{{ route('unites-enseignement.index') }}" class="btn btn-danger px-4"><i class="bi bi-x-circle"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Custom CSS -->
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
    }

    .btn-success, .btn-danger {
        border-radius: 8px;
        font-weight: bold;
    }

    .form-label {
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
    }
</style>

@endsection
