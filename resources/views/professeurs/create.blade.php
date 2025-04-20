@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Ajouter un Professeur</h3>

    <form action="{{ route('professeurs.store') }}" method="POST">
        @csrf

        <!-- Sélectionner un utilisateur -->
<div class="mb-3">
    <label for="id_user" class="form-label">Utilisateur</label>
    <select name="id_user" id="id_user" class="form-control" required>
        <option value="">Sélectionner un utilisateur</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                {{ $user->nom }} - {{ $user->email }} ({{ ucfirst($user->role) }})
            </option>
        @endforeach
    </select>
    @error('id_user')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

        <!-- Adresse -->
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse"
                   value="{{ old('adresse') }}" required>
            @error('adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Matricule -->
        <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="text" class="form-control" id="matricule" name="matricule"
                   value="{{ old('matricule') }}" required>
            @error('matricule')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Grade -->
        <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade"
                   value="{{ old('grade') }}" required>
            @error('grade')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Régime d'emploi -->
        <div class="mb-3">
            <label for="regime_emploi" class="form-label">Régime d'Emploi</label>
            <input type="text" class="form-control" id="regime_emploi" name="regime_emploi"
                   value="{{ old('regime_emploi') }}" required>
            @error('regime_emploi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Spécialité (optionnel) -->
        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité (optionnel)</label>
            <input type="text" class="form-control" id="specialite" name="specialite"
                   value="{{ old('specialite') }}">
            @error('specialite')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date de prise de fonction -->
        <div class="mb-3">
            <label for="date_prise_fonction" class="form-label">Date de Prise de Fonction</label>
            <input type="date" class="form-control" id="date_prise_fonction" name="date_prise_fonction"
                   value="{{ old('date_prise_fonction') }}" required>
            @error('date_prise_fonction')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Date de fin de mandat (optionnel) -->
        <div class="mb-3">
            <label for="date_fin_mandat" class="form-label">Date de Fin de Mandat (optionnel)</label>
            <input type="date" class="form-control" id="date_fin_mandat" name="date_fin_mandat"
                   value="{{ old('date_fin_mandat') }}">
            @error('date_fin_mandat')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Affiliation (optionnel) -->
        <div class="mb-3">
            <label for="affiliation" class="form-label">Affiliation (optionnel)</label>
            <input type="text" class="form-control" id="affiliation" name="affiliation"
                   value="{{ old('affiliation') }}">
            @error('affiliation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

<select name="matieres[]" id="matieres" class="form-control" multiple required>
    @foreach ($matieres as $matiere)
        <option value="{{ $matiere->id_matiere }}"
            {{ in_array($matiere->id_matiere, old('matieres', [])) ? 'selected' : '' }}>
            {{-- Affichage : intitule (niveau_scolaire) --}}
            {{ $matiere->intitule }}
            ({{ $matiere->uniteEnseignement->niveau_scolaire ?? 'N/A' }})
        </option>
    @endforeach
</select>


<!-- Date de début des cours -->
<div class="mb-3">
    <label for="date_debut" class="form-label">Date de Début de l'enseignement</label>
    <input type="date" class="form-control" id="date_debut" name="date_debut" required>
    @error('date_debut')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Date de fin des cours -->
<div class="mb-3">
    <label for="date_fin" class="form-label">Date de Fin de l'enseignement</label>
    <input type="date" class="form-control" id="date_fin" name="date_fin" required>
    @error('date_fin')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Volume horaire effectif -->
<div class="mb-3">
    <label for="vh_effectif" class="form-label">Volume Horaire Effectif</label>
    <input type="number" class="form-control" id="vh_effectif" name="vh_effectif" min="0" required>
    @error('vh_effectif')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>





        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Créer le Professeur</button>
    </form>
</div>
@endsection
