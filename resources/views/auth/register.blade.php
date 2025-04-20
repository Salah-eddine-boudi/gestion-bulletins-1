@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 80vh;">
    <div class="card shadow-lg p-4 bg-white animate__animated animate__fadeIn" style="width: 400px; border-radius: 15px;">
        <h2 class="text-center text-primary mb-4">Créer un Compte</h2>

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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Champ : Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    class="form-control @error('nom') is-invalid @enderror"
                    required
                    value="{{ old('nom') }}"
                >
                @error('nom')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Prénom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    class="form-control @error('prenom') is-invalid @enderror"
                    required
                    value="{{ old('prenom') }}"
                >
                @error('prenom')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    class="form-control @error('nom') is-invalid @enderror"
                    required
                    value="{{ old('nom') }}"
                >
                @error('nom')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    required
                    value="{{ old('email') }}"
                >
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Téléphone Professionnel (tel_pro) -->
            <div class="mb-3">
                <label for="tel_pro" class="form-label">Téléphone professionnel :</label>
                <input
                    type="text"
                    id="tel_pro"
                    name="tel_pro"
                    class="form-control @error('tel_pro') is-invalid @enderror"
                    value="{{ old('tel_pro') }}"
                >
                @error('tel_pro')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Statut -->
            <div class="mb-3">
                <label for="statut" class="form-label">Statut :</label>
                <select
                    id="statut"
                    name="statut"
                    class="form-select @error('statut') is-invalid @enderror"
                >
                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Rôle -->
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-select">
                    <option value="" disabled selected>Choisissez votre rôle</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ADMIN</option>
                    <option value="professeur" {{ old('role') == 'professeur' ? 'selected' : '' }}>PROFESSEUR</option>
                    <option value="eleve" {{ old('role') == 'eleve' ? 'selected' : '' }}>ELEVE</option>
                    <option value="directeur" {{ old('role') == 'directeur' ? 'selected' : '' }}>DIRECTEUR Pédagogique</option>
                </select>
                @error('role')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                >
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ : Confirmation du mot de passe -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe :</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    required
                >
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <span>Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="text-primary">Se Connecter</a>
        </div>
    </div>
</div>
@endsection
