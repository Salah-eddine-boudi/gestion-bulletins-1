@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter un Élève</h2>

    <!-- Affichage des erreurs -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Affichage du message de succès -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('eleves.store') }}" method="POST">
        @csrf

        <!-- Champ Utilisateur -->
        <div class="mb-3">
            <label for="id_user" class="form-label">Utilisateur</label>
            <select name="id_user" id="id_user" class="form-control" required>
                <option value="">Sélectionner un utilisateur</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->nom }} - {{ $user->email }}</option>
                @endforeach
            </select>
        </div>

        <!-- Champ Date de naissance -->
        <div class="mb-3">
            <label for="date_naissance" class="form-label">Date de naissance</label>
            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
        </div>

        <!-- Champ Matricule -->
        <div class="mb-3">
            <label for="matricule" class="form-label">Matricule</label>
            <input type="text" class="form-control" id="matricule" name="matricule" required>
        </div>

        <!-- Champ Niveau Scolaire -->
        <div class="mb-3">
            <label for="niveau_scolaire" class="form-label">Niveau</label>
            <input type="text" class="form-control" id="niveau_scolaire" name="niveau_scolaire" required>
        </div>

        <!-- Champ Spécialité -->
        <div class="mb-3">
            <label for="specialite" class="form-label">Spécialité</label>
            <input type="text" class="form-control" id="specialite" name="specialite">
        </div>

        <!-- Champ Date d'inscription -->
        <div class="mb-3">
            <label for="date_inscription" class="form-label">Date d'inscription</label>
            <input type="date" class="form-control" id="date_inscription" name="date_inscription" value="{{ now()->toDateString() }}" required>
        </div>

        <!-- Champ Statut -->
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">Sélectionner le statut</option>
                <option value="actif">Actif</option>
                <option value="archivé">Archivé</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter l'Élève</button>
    </form>
</div>
@endsection
