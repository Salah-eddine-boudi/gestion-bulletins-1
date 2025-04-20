@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter un Directeur Pédagogique</h2>

    <!-- Affichage des erreurs si elles existent -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('directeurs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_user" class="form-label">Utilisateur</label>
            <select name="id_user" id="id_user" class="form-control" required>
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }} - {{ $user->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_prof" class="form-label">Professeur (optionnel)</label>
            <select name="id_prof" id="id_prof" class="form-control">
                <option value="">Sélectionner un professeur</option>
                @foreach ($professeurs as $professeur)
                    <option value="{{ $professeur->id_prof }}">{{ $professeur->matricule }} - {{ $professeur->grade }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date_prise_fonction" class="form-label">Date de Prise de Fonction</label>
            <input type="date" class="form-control" id="date_prise_fonction" name="date_prise_fonction" required>
        </div>

        <div class="mb-3">
            <label for="date_fin_mandat" class="form-label">Date de Fin de Mandat (optionnel)</label>
            <input type="date" class="form-control" id="date_fin_mandat" name="date_fin_mandat">
        </div>

        <div class="mb-3">
            <label for="tel" class="form-label">Téléphone (optionnel)</label>
            <input type="text" class="form-control" id="tel" name="tel">
        </div>

        <div class="mb-3">
            <label for="bureau" class="form-label">Bureau (optionnel)</label>
            <input type="text" class="form-control" id="bureau" name="bureau">
        </div>

        <div class="mb-3">
            <label for="appreciation" class="form-label">Appréciation (optionnel)</label>
            <textarea class="form-control" id="appreciation" name="appreciation"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Créer le Directeur Pédagogique</button>
    </form>
</div>
@endsection
