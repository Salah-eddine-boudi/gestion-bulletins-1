@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container">
    <h2>Détails de la Matière : {{ $matiere->intitule }}</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Code :</strong> {{ $matiere->code }}</li>
        <li class="list-group-item"><strong>Année Universitaire :</strong> {{ $matiere->annee_universitaire }}</li>
        <li class="list-group-item"><strong>Semestre :</strong> {{ $matiere->semestre }}</li>
    </ul>

    <a href="{{ route('matieres.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
</div> 

@else
    <div class="alert alert-danger text-center mt-5">
        <h4>Accès interdit !</h4>
        <p>Vous devez être connecté pour accéder à cette page.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">🔐 Se connecter</a>
    </div>
@endif
@endsection
