@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)
@section('content')
@if (Auth::check())
<div class="container">
    <h2>Modifier l'Élève : {{ optional($eleve->user)->nom ?? 'Utilisateur non trouvé' }}</h2>
    <form action="{{ route('eleves.update', $eleve) }}" method="POST">


        @csrf @method('PUT')
        
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

<!-- Vue show.blade.php -->
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)
@section('content')
@if (Auth::check())
<div class="container">
    <h2>Détails de l'Élève</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ optional($eleve->user)->nom ?? 'Utilisateur non trouvé' }} {{ optional($eleve->user)->prenom ?? '' }}</h5>
            <p class="card-text"><strong>Email :</strong> {{ optional($eleve->user)->email ?? 'Aucun email' }}</p>
            <p class="card-text"><strong>Matricule :</strong> {{ $eleve->matricule }}</p>
            <p class="card-text"><strong>Niveau :</strong> {{ $eleve->niveau }}</p>
            <p class="card-text"><strong>Spécialité :</strong> {{ $eleve->specialite ?? 'Non spécifiée' }}</p>
            <p class="card-text"><strong>Date de naissance :</strong> {{ $eleve->date_naissance }}</p>
            <p class="card-text"><strong>Date d'inscription :</strong> {{ $eleve->date_inscription }}</p>
            <p class="card-text"><strong>Statut :</strong> {{ ucfirst($eleve->status) }}</p>
            <a href="{{ route('eleves.index') }}" class="btn btn-primary">Retour</a>
        </div>
    </div>
</div>
@endif
@endsection
