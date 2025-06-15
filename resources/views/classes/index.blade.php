<!-- resources/views/classes/index.blade.php -->

@php
    $layouts = ['app', 'admin', 'admin2'];
    $layout = session('layout', 'app');
    if (!in_array($layout, $layouts)) $layout = 'app';
    $layoutPath = 'layouts.' . $layout;
@endphp

@extends($layoutPath)

@section('content')
<div class="container">
    <h1>Liste des Classes (Groupes de Langue)</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('classes.create') }}" class="btn btn-primary mb-3">
        Créer une nouvelle Classe (Groupe)
    </a>

    @if ($classes->count() > 0)
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Langue</th>
                <th>Niveau</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Semestre</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($classes as $classe)
                <tr>
                    <td>{{ $classe->id }}</td>
                    <td>{{ $classe->langue }}</td>
                    <td>{{ $classe->niveau }}</td>
                    <td>{{ $classe->date_debut }}</td>
                    <td>{{ $classe->date_fin }}</td>
                    <td>{{ $classe->semestre }}</td>
                    <td>
                        <a href="{{ route('classes.show', $classe->id) }}" class="btn btn-info">
                            Détails
                        </a>
                        <a href="{{ route('classes.edit', $classe->id) }}" class="btn btn-warning">
                            Modifier
                        </a>
                        <form action="{{ route('classes.destroy', $classe->id) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger"
                                    onclick="return confirm('Voulez-vous vraiment supprimer cette classe ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            Aucune classe à afficher pour le moment.
        </div>
    @endif
</div>
@endsection
