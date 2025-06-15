@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h3 class="mb-4">Modifier un Directeur Pédagogique</h3>

    <form action="{{ route('directeurs.update', $directeur->id_dp) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Sélectionner l'user pour le directeur pédagogique -->
        <div class="mb-3">
            <label for="id_user" class="form-label">User</label>
            <select name="id_user" id="id_user" class="form-control" required>
                <option value="">Sélectionner un user</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" 
                        @if($user->id == $directeur->id_user) selected @endif>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Nom et Prénom de l'user -->
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" 
                value="{{ $directeur->user ? $directeur->user->prenom : '' }}" required>
        </div>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" 
                value="{{ $directeur->user ? $directeur->user->nom : '' }}" required>
        </div>

        <!-- Professeur (optionnel) -->
        <div class="mb-3">
            <label for="id_prof" class="form-label">Professeur (optionnel)</label>
            <select name="id_prof" id="id_prof" class="form-control">
                <option value="">Sélectionner un professeur</option>
                @foreach ($professeurs as $professeur)
                    <option value="{{ $professeur->id_prof }}" 
                        @if($professeur->id_prof == $directeur->id_prof) selected @endif>
                        {{ $professeur->matricule }} - {{ $professeur->grade }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date de prise de fonction -->
        <div class="mb-3">
            <label for="date_prise_fonction" class="form-label">Date de Prise de Fonction</label>
            <input type="date" class="form-control" id="date_prise_fonction" name="date_prise_fonction" value="{{ $directeur->date_prise_fonction }}" required>
        </div>

        <!-- Date de fin de mandat (optionnel) -->
        <div class="mb-3">
            <label for="date_fin_mandat" class="form-label">Date de Fin de Mandat (optionnel)</label>
            <input type="date" class="form-control" id="date_fin_mandat" name="date_fin_mandat" value="{{ $directeur->date_fin_mandat }}">
        </div>

        <!-- Téléphone (optionnel) -->
        <div class="mb-3">
            <label for="tel" class="form-label">Téléphone (optionnel)</label>
            <input type="text" class="form-control" id="tel" name="tel" value="{{ $directeur->tel }}">
        </div>

        <!-- Bureau (optionnel) -->
        <div class="mb-3">
            <label for="bureau" class="form-label">Bureau (optionnel)</label>
            <input type="text" class="form-control" id="bureau" name="bureau" value="{{ $directeur->bureau }}">
        </div>

        <!-- Appréciation (optionnel) -->
        <div class="mb-3">
            <label for="appreciation" class="form-label">Appréciation (optionnel)</label>
            <textarea class="form-control" id="appreciation" name="appreciation">{{ $directeur->appreciation }}</textarea>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>
@endsection
