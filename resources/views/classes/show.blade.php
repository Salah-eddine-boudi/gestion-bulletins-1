<!-- resources/views/classes/show.blade.php -->
@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

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
