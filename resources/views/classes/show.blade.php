<!-- resources/views/classes/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la Classe</h1>

    <div class="card">
        <div class="card-header">
            Classe #{{ $classe->id }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Nom : {{ $classe->nom }}</h5>
            <p class="card-text">
                <strong>Niveau :</strong> {{ $classe->niveau }} <br>
                <strong>Spécialité :</strong> {{ $classe->specialite }} <br>
                <strong>Année Scolaire :</strong> {{ $classe->annee_scolaire }}
            </p>
        </div>
    </div>

    <a href="{{ route('classes.index') }}" class="btn btn-secondary mt-3">
        Retour à la liste
    </a>
</div>
@endsection
