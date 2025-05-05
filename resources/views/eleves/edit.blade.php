@extends('layouts.app')

@section('content')
@if (Auth::check())
<div class="container">
    <h2>Modifier l'Élève : {{ optional($eleve->user)->nom ?? 'Utilisateur non trouvé' }}</h2>
    <form method="POST" action="{{ route('eleves.update', $eleve->id_eleve) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select name="genre" id="genre" class="form-control">
                <option value="">Sélectionner un genre</option>
                <option value="M" {{ $eleve->genre == 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ $eleve->genre == 'F' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" name="date_naissance" class="form-control" value="{{ $eleve->date_naissance }}" required>
        </div>

        <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="text" name="matricule" class="form-control" value="{{ $eleve->matricule }}" required>
        </div>

        <div class="mb-3">
            <label for="niveau" class="form-label">Niveau</label>
            <input type="text" name="niveau" class="form-control" value="{{ $eleve->niveau }}" required>
        </div>

        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité</label>
            <input type="text" name="specialite" class="form-control" value="{{ $eleve->specialite }}">
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>
@endif
@endsection