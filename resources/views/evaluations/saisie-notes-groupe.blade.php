@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Saisie des évaluations par groupe de langue</h3>

    <!-- Formulaire pour sélectionner un groupe de langue et un niveau -->
    <form action="{{ route('saisie-notes-groupe') }}" method="GET">
        @csrf
        <div class="mb-3">
            <label for="id_groupe_langue" class="form-label">Sélectionner un groupe de langue</label>
            <select name="id_groupe_langue" id="id_groupe_langue" class="form-control" required>
                <option value="">Sélectionner un groupe de langue</option>
                @foreach($groupes as $groupe)
                    <option value="{{ $groupe->id_groupe_langue }}" {{ request('id_groupe_langue') == $groupe->id_groupe_langue ? 'selected' : '' }}>
                        {{ $groupe->langue }} - Niveau {{ $groupe->niveau }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Sélectionner le niveau -->
        <div class="mb-3">
            <label for="niveau" class="form-label">Sélectionner le niveau</label>
            <select name="niveau" id="niveau" class="form-control" required>
                <option value="débutant" {{ request('niveau') == 'débutant' ? 'selected' : '' }}>Débutant</option>
                <option value="intermédiaire" {{ request('niveau') == 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                <option value="avancé" {{ request('niveau') == 'avancé' ? 'selected' : '' }}>Avancé</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf

        <!-- Table des élèves -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Nom de l'élève</th>
                    <th>Note</th>
                    <th>Présence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleves as $eleve)
                    <tr>
                        <td>{{ $eleve->user->prenom }} {{ $eleve->user->nom }}</td>
                        <td>
                            <input type="number" name="notes[{{ $eleve->id_eleve }}][note]" class="form-control" min="0" max="20" required>
                        </td>
                        <td>
                            <select name="notes[{{ $eleve->id_eleve }}][presence]" class="form-control" required>
                                <option value="Présent">Présent</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Ajouter les évaluations</button>
    </form>
</div>
@endsection
