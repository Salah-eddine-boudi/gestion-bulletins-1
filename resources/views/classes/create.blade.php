<!-- resources/views/classes/create.blade.php -->
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h1>Créer une nouvelle Classe</h1>

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

    <form action="{{ route('classes.store') }}" method="POST">
        @csrf
        <!-- Nom de la classe (champ text) -->
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la classe</label>
            <input type="text"
                   name="nom"
                   id="nom"
                   class="form-control"
                   value="{{ old('nom') }}"
                   required>
        </div>

        <!-- Sélecteur du niveau -->
        <div class="mb-3">
            <label for="niveau" class="form-label">Niveau</label>
            <select name="niveau" id="niveau" class="form-control">
                <option value="">-- Sélectionnez un niveau --</option>
                <option value="JM1" {{ old('niveau') == 'JM1' ? 'selected' : '' }}>JM1</option>
                <option value="JM2" {{ old('niveau') == 'JM2' ? 'selected' : '' }}>JM2</option>
                <option value="JM3" {{ old('niveau') == 'JM3' ? 'selected' : '' }}>JM3</option>
                <!-- Ajoutez d’autres niveaux si nécessaire -->
            </select>
        </div>

        <!-- Spécialité (champ texte) : si JM3, on peut mettre ISEN, HEI, etc. -->
        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité</label>
            <input type="text"
                   name="specialite"
                   id="specialite"
                   class="form-control"
                   value="{{ old('specialite') }}">
        </div>

        <!-- Sélecteur pour l'année scolaire -->
        <div class="mb-3">
            <label for="annee_scolaire" class="form-label">Année Scolaire</label>
            <select name="annee_scolaire" id="annee_scolaire" class="form-control">
                <option value="">-- Sélectionnez l'année --</option>
                <option value="2023/2024" {{ old('annee_scolaire') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                <option value="2024/2025" {{ old('annee_scolaire') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                <!-- Ajoutez d’autres années si nécessaire -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
