@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h2>Modifier un Professeur</h2>

    <!-- Affichage du message de succès si disponible -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulaire d'édition -->
    <form action="{{ route('professeurs.update', $professeur->id_prof) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Sélectionner un utilisateur -->
        <div class="mb-3">
            <label for="id_user" class="form-label">Utilisateur</label>
            <select name="id_user" id="id_user" class="form-control" required>
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $professeur->id_user ? 'selected' : '' }}>
                        {{ $user->prenom }} {{ $user->nom }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
            @error('id_user')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ d'adresse -->
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse', $professeur->adresse) }}" required>
            @error('adresse')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ matricule -->
        <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="text" class="form-control" id="matricule" name="matricule" value="{{ old('matricule', $professeur->matricule) }}" required>
            @error('matricule')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ grade -->
        <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade" value="{{ old('grade', $professeur->grade) }}" required>
            @error('grade')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ régime emploi -->
        <div class="mb-3">
            <label for="regime_emploi" class="form-label">Régime d'emploi</label>
            <input type="text" class="form-control" id="regime_emploi" name="regime_emploi" value="{{ old('regime_emploi', $professeur->regime_emploi) }}" required>
            @error('regime_emploi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ spécialité -->
        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité</label>
            <input type="text" class="form-control" id="specialite" name="specialite" value="{{ old('specialite', $professeur->specialite) }}">
            @error('specialite')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ date de prise de fonction -->
        <div class="mb-3">
            <label for="date_prise_fonction" class="form-label">Date de prise de fonction</label>
            <input type="date" class="form-control" id="date_prise_fonction" name="date_prise_fonction" value="{{ old('date_prise_fonction', $professeur->date_prise_fonction) }}" required>
            @error('date_prise_fonction')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ date de fin de mandat -->
        <div class="mb-3">
            <label for="date_fin_mandat" class="form-label">Date de fin de mandat</label>
            <input type="date" class="form-control" id="date_fin_mandat" name="date_fin_mandat" value="{{ old('date_fin_mandat', $professeur->date_fin_mandat) }}">
            @error('date_fin_mandat')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ affiliation -->
        <div class="mb-3">
            <label for="affiliation" class="form-label">Affiliation</label>
            <input type="text" class="form-control" id="affiliation" name="affiliation" value="{{ old('affiliation', $professeur->affiliation) }}">
            @error('affiliation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
