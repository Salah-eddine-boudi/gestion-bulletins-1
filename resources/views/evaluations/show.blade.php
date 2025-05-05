@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-secondary text-white">
            <h3><i class="bi bi-eye"></i> Détails de l'Évaluation</h3>
        </div>
        <div class="card-body">
            {{-- Informations générales --}}
            <dl class="row mb-4">
                <dt class="col-sm-3">Matière :</dt>
                <dd class="col-sm-9">{{ $evaluation->matiere->intitule }}</dd>

                <dt class="col-sm-3">Type :</dt>
                <dd class="col-sm-9">{{ $evaluation->type }}</dd>

                <dt class="col-sm-3">Date :</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($evaluation->date_evaluation)->format('d/m/Y') }}</dd>
            </dl>

            {{-- Tableau des notes --}}
            <h5>Notes et présences des élèves</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Élève</th>
                        <th>Présence</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluation->notes as $note)
                        <tr>
                            <td>{{ $note->eleve->user->prenom }} {{ $note->eleve->user->nom }}</td>
                            <td>{{ $note->presence }}</td>
                            <td>
                                @if($note->presence === 'Absent')
                                    —
                                @else
                                    {{ $note->note }}/20
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Aucune note enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Boutons d'action --}}
            <div class="mt-4">
                <a href="{{ route('evaluations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
                <a href="{{ route('evaluations.edit', $evaluation) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <form action="{{ route('evaluations.destroy', $evaluation) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Supprimer définitivement cette évaluation ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
