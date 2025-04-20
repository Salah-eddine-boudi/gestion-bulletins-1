@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Modifier le Paramétrage d'Évaluation</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluation-params.update', $param->id_config) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Professeur (ID)</label>
                <input type="number" name="id_professeur" class="form-control" value="{{ old('id_professeur', $param->id_professeur) }}" required>
            </div>
            <div class="col-md-4">
                <label>Matière (ID)</label>
                <input type="number" name="id_matiere" class="form-control" value="{{ old('id_matiere', $param->id_matiere) }}" required>
            </div>
            <div class="col-md-4">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="DS" {{ $param->type == 'DS' ? 'selected' : '' }}>DS</option>
                    <option value="EXAM" {{ $param->type == 'EXAM' ? 'selected' : '' }}>EXAM</option>
                    <option value="RATTRAPAGE" {{ $param->type == 'RATTRAPAGE' ? 'selected' : '' }}>RATTRAPAGE</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nombre d'évaluations</label>
                <input type="number" name="nombre_evaluations" class="form-control" value="{{ old('nombre_evaluations', $param->nombre_evaluations) }}" min="1" required>
            </div>
            <div class="col-md-6">
                <label>Pourcentage (%)</label>
                <input type="number" name="pourcentage" class="form-control" value="{{ old('pourcentage', $param->pourcentage) }}" step="0.01" min="0" max="100" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('evaluation-params.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
