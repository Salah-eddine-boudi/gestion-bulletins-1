@extends('layouts.app')

@section('content')
@if (Auth::check()) 
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Détails de l'Unité d'Enseignement</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th><i class="bi bi-book"></i> Intitulé</th>
                        <td>{{ $ue->intitule }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-tags"></i> Type</th>
                        <td>{{ $ue->type }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-mortarboard"></i> Niveau</th>
                        <td>{{ $ue->niveau_scolaire }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-hash"></i> Code</th>
                        <td>{{ $ue->code }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-file-text"></i> Description</th>
                        <td>{{ $ue->description ?? 'Aucune description' }}</td>
                    </tr>
                    <tr>
                        <th><i class="bi bi-calendar"></i> Année Universitaire</th>
                        <td>{{ $ue->annee_universitaire }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('unites-enseignement.index') }}" class="btn btn-secondary">🔙 Retour</a>
                <div>
                    <a href="{{ route('unites-enseignement.edit', $ue->id_ue) }}" class="btn btn-warning">✏ Modifier</a>
                    <form action="{{ route('unites-enseignement.destroy', $ue->id_ue) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">🗑 Supprimer</button>
                    </form>
                </div>
            </div>
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

    .btn-warning, .btn-danger, .btn-secondary {
        border-radius: 8px;
        font-weight: bold;
    }

    table th {
        width: 30%;
        background-color: #f8f9fa;
    }
</style>

@endsection
