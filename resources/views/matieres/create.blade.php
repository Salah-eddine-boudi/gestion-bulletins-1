@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container">
    <h2>Ajouter une Matière</h2>
    <form action="{{ route('matieres.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="intitule" class="form-label">Intitulé</label>
            <input type="text" class="form-control" id="intitule" name="intitule" required>
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" placeholder="EX: CODE-1234" required>
        </div>

        <div class="mb-3">
            <label for="id_ue" class="form-label">Unité d'Enseignement</label>
            <select class="form-control" id="id_ue" name="id_ue" required>
                <option value="">Sélectionner une unité</option>
                @foreach($unites as $unite)
                    <option value="{{ $unite->id_ue }}">{{ $unite->intitule }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="volume_horaire" class="form-label">Volume Horaire</label>
            <input type="number" class="form-control" id="volume_horaire" name="volume_horaire">
        </div>

        <div class="mb-3">
            <label for="syllabus" class="form-label">Syllabus</label>
            <textarea class="form-control" id="syllabus" name="syllabus"></textarea>
        </div>

        <div class="mb-3">
            <label for="ects" class="form-label">ECTS</label>
            <input type="number" class="form-control" id="ects" name="ects">
        </div>

        <div class="mb-3">
            <label for="filiere" class="form-label">Filière</label>
            <select class="form-control" id="filiere" name="filiere" required>
                <option value="Tronc Commun">Tronc Commun</option>
                <option value="ISEN">ISEN</option>
                <option value="HEI">HEI</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="date_creation" class="form-label">Date de Création</label>
            <input type="date" class="form-control" id="date_creation" name="date_creation">
        </div>

        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de Fin</label>
            <input type="date" class="form-control" id="date_fin" name="date_fin">
        </div>

        <div class="mb-3">
            <label for="est_validante" class="form-label">Est Validante</label>
            <select class="form-control" id="est_validante" name="est_validante">
                <option value="1">Oui</option>
                <option value="0">Non</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="annee_universitaire" class="form-label">Année Universitaire</label>
            <select class="form-control" id="annee_universitaire" name="annee_universitaire" required>
                <option value="2023/2024">2023/2024</option>
                <option value="2024/2025">2024/2025</option>
                <option value="2025/2026">2025/2026</option>
                <!-- Ajoutez d'autres années scolaires ici -->
            </select>
        </div>

        <div class="mb-3">
            <label for="semestre" class="form-label">Semestre</label>
            <select class="form-control" id="semestre" name="semestre" required>
                <option value="S1">Semestre 1</option>
                <option value="S2">Semestre 2</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
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
