@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Liste des Paramétrages d'Évaluations</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('evaluation-params.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Ajouter un nouveau paramétrage
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Professeur (ID)</th>
                <th>Matière (ID)</th>
                <th>Type</th>
                <th>Nombre d'évaluations</th>
                <th>Pourcentage (%)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($params as $param)
            <tr>
                <td>{{ $param->id_config }}</td>
                <td>{{ $param->id_professeur }}</td>
                <td>{{ $param->id_matiere }}</td>
                <td>{{ $param->type }}</td>
                <td>{{ $param->nombre_evaluations }}</td>
                <td>{{ $param->pourcentage }}</td>
                <td>
                    <a href="{{ route('evaluation-params.edit', $param->id_config) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('evaluation-params.destroy', $param->id_config) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmez-vous la suppression ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
