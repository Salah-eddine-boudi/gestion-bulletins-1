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
            <h3><i class="bi bi-pencil-square"></i> Modifier l'Unité d'Enseignement</h3>
        </div>
        <div class="card-body">

            {{-- Affichage des erreurs --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulaire d'édition --}}
            <form action="{{ route('unites-enseignement.update', $unite) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Intitulé --}}
                    <div class="col-md-6 mb-3">
                        <label for="intitule" class="form-label fw-bold">
                            <i class="bi bi-book"></i> Intitulé
                        </label>
                        <input 
                            type="text" 
                            id="intitule"
                            name="intitule" 
                            class="form-control @error('intitule') is-invalid @enderror" 
                            value="{{ old('intitule', $unite->intitule) }}" 
                            required>
                        @error('intitule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label fw-bold">
                            <i class="bi bi-tags"></i> Type
                        </label>
                        <input 
                            type="text" 
                            id="type"
                            name="type" 
                            class="form-control @error('type') is-invalid @enderror" 
                            value="{{ old('type', $unite->type) }}" 
                            required>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Niveau scolaire --}}
                    <div class="col-md-6 mb-3">
                        <label for="niveau_scolaire" class="form-label fw-bold">
                            <i class="bi bi-mortarboard"></i> Niveau Scolaire
                        </label>
                        <input 
                            type="text" 
                            id="niveau_scolaire"
                            name="niveau_scolaire" 
                            class="form-control @error('niveau_scolaire') is-invalid @enderror" 
                            value="{{ old('niveau_scolaire', $unite->niveau_scolaire) }}" 
                            required>
                        @error('niveau_scolaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Code --}}
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label fw-bold">
                            <i class="bi bi-hash"></i> Code
                        </label>
                        <input 
                            type="text" 
                            id="code"
                            name="code" 
                            class="form-control @error('code') is-invalid @enderror" 
                            value="{{ old('code', $unite->code) }}" 
                            required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">
                        <i class="bi bi-file-text"></i> Description
                    </label>
                    <textarea 
                        id="description"
                        name="description" 
                        class="form-control @error('description') is-invalid @enderror" 
                        rows="3"
                    >{{ old('description', $unite->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Année universitaire --}}
                    <div class="col-md-6 mb-3">
                        <label for="annee_universitaire" class="form-label fw-bold">
                            <i class="bi bi-calendar"></i> Année Universitaire
                        </label>
                        <input 
                            type="text" 
                            id="annee_universitaire"
                            name="annee_universitaire" 
                            class="form-control @error('annee_universitaire') is-invalid @enderror" 
                            value="{{ old('annee_universitaire', $unite->annee_universitaire) }}" 
                            required>
                        @error('annee_universitaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="bi bi-check-circle"></i> Mettre à jour
                    </button>
                    <a href="{{ route('unites-enseignement.index') }}" class="btn btn-danger px-4">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Styles additionnels (à extraire si besoin) --}}
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .btn-warning, .btn-danger {
        border-radius: 8px;
        font-weight: bold;
    }
    .form-label {
        font-size: 1rem;
    }
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 10px rgba(255,193,7,0.25);
    }
</style>

@endsection
