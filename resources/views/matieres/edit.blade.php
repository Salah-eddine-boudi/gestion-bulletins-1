@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container">
    <h2>Modifier la Matière : {{ $matiere->intitule }}</h2>
    <form action="{{ route('matieres.update', $matiere->id_matiere) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="intitule" class="form-label">Intitulé</label>
            <input type="text" class="form-control" id="intitule" name="intitule" value="{{ $matiere->intitule }}" required>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $matiere->code }}" required placeholder="EX: CODE-1234">
        </div>

        <div class="mb-3">
            <label for="annee_universitaire" class="form-label">Année Universitaire</label>
            <select class="form-control" id="annee_universitaire" name="annee_universitaire" required>
                <option value="2023/2024" {{ $matiere->annee_universitaire == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                <option value="2024/2025" {{ $matiere->annee_universitaire == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                <option value="2025/2026" {{ $matiere->annee_universitaire == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                <!-- Vous pouvez ajouter d'autres options si nécessaire -->
            </select>
        </div>

        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre</label>
            <select class="form-control" id="semestre" name="semestre" required>
                <option value="S1" {{ $matiere->semestre == 'S1' ? 'selected' : '' }}>Semestre 1</option>
                <option value="S2" {{ $matiere->semestre == 'S2' ? 'selected' : '' }}>Semestre 2</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="filiere" class="form-label">Filière</label>
            <select class="form-control" id="filiere" name="filiere" required>
                <option value="Tronc Commun" {{ $matiere->filiere == 'Tronc Commun' ? 'selected' : '' }}>Tronc Commun</option>
                <option value="ISEN" {{ $matiere->filiere == 'ISEN' ? 'selected' : '' }}>ISEN</option>
                <option value="HEI" {{ $matiere->filiere == 'HEI' ? 'selected' : '' }}>HEI</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>

@else
    <div class="alert alert-danger text-center mt-5">
        <h4>Accès interdit !</h4>
        <p>Vous devez être connecté pour accéder à cette page.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">🔐 Se connecter</a>
    </div>
@endif
@endsection
