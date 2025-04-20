@extends('layouts.app')

@section('content')
@if (Auth::check()) 
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Liste des Unités d'Enseignement</h3>
            </div>
            <div class="card-body">

                <!-- Affichage du message de succès -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold">Total des unités : {{ count($unites) }}</h5>
                    <a href="{{ route('unites-enseignement.create') }}" class="btn btn-success">➕ Ajouter une Unité</a>
                </div>

                <!-- Vérification si des unités existent -->
                @if (isset($unites) && count($unites) > 0)
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Intitulé</th>
                                <th>Type</th>
                                <th>Niveau</th>
                                <th>Code</th>
                                <th>Année Universitaire</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unites as $ue)
                            <tr>
                                <td>{{ $ue->id_ue }}</td>
                                <td>{{ $ue->intitule }}</td>
                                <td>{{ $ue->type }}</td>
                                <td>{{ $ue->niveau_scolaire }}</td>
                                <td>{{ $ue->code }}</td>
                                <td>{{ $ue->annee_universitaire }}</td>
                                <td class="text-center">
                                    <a href="{{ route('unites-enseignement.show', $ue->id_ue) }}" class="btn btn-info btn-sm">👁 Voir</a>
                                    <a href="{{ route('unites-enseignement.edit', $ue->id_ue) }}" class="btn btn-warning btn-sm">✏ Modifier</a>
                                    <form action="{{ route('unites-enseignement.destroy', $ue->id_ue) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">🗑 Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning text-center">
                        <h5>Aucune unité d'enseignement disponible.</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger text-center mt-5">
        <h4>Accès interdit !</h4>
        <p>Vous devez être connecté pour accéder à cette page.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">🔐 Se connecter</a>
    </div>
@endif

<!-- Custom CSS -->
<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .btn-success, .btn-danger, .btn-warning, .btn-info {
        border-radius: 8px;
        font-weight: bold;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

@endsection
