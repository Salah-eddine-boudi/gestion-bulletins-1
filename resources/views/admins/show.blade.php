@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h3 class="mb-4">Détails de l'Administrateur</h3>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informations personnelles</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>ID Admin:</strong> {{ $admin->id_admin }}</li>
                <li class="list-group-item"><strong>Nom complet:</strong> {{ $admin->user->prenom ?? 'N/A' }} {{ $admin->user->nom ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $admin->user->email ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Téléphone:</strong> {{ $admin->tel ?? 'Non renseigné' }}</li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Détails du Poste</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Rôle:</strong> {{ $admin->role }}</li>
                <li class="list-group-item"><strong>Accès:</strong> {{ $admin->acces }}</li>
                <li class="list-group-item"><strong>Bureau:</strong> {{ $admin->bureau }}</li>
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Retour</a>
        <a href="{{ route('admins.edit', $admin->id_admin) }}" class="btn btn-warning">Modifier</a>

        <form action="{{ route('admins.destroy', $admin->id_admin) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?');">
                Supprimer
            </button>
        </form>
    </div>
</div>
@endsection
