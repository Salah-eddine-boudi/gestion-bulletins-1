<!-- resources/views/classes/edit.blade.php -->
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h1>Modifier la Classe</h1>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('classes.update', $classe->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la classe</label>
            <input type="text"
                   name="nom"
                   id="nom"
                   class="form-control"
                   value="{{ old('nom', $classe->nom) }}"
                   required>
        </div>

        <div class="mb-3">
            <label for="niveau" class="form-label">Niveau (ex: JM1, JM2, JM3...)</label>
            <input type="text"
                   name="niveau"
                   id="niveau"
                   class="form-control"
                   value="{{ old('niveau', $classe->niveau) }}">
        </div>

        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité (ISEN, HEI...)</label>
            <input type="text"
                   name="specialite"
                   id="specialite"
                   class="form-control"
                   value="{{ old('specialite', $classe->specialite) }}">
        </div>

        <div class="mb-3">
            <label for="annee_scolaire" class="form-label">Année Scolaire</label>
            <input type="text"
                   name="annee_scolaire"
                   id="annee_scolaire"
                   class="form-control"
                   value="{{ old('annee_scolaire', $classe->annee_scolaire) }}">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
