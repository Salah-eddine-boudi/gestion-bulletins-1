@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center"><i class="fas fa-edit"></i> Modifier l'évaluation de l'élève</h3>
                </div>

                <div class="card-body">
                    <!-- Formulaire d'édition -->
                    <form action="{{ route('evaluations.update', $evaluation->id_evaluation) }}" method="POST">
                        @csrf
                        @method('PUT') 

                        <!-- Élève -->
                        <div class="mb-3">
                            <label for="id_eleve" class="form-label"><i class="fas fa-user-graduate"></i> Élève</label>
                            <select name="id_eleve" id="id_eleve" class="form-select shadow-sm" required>
                                @foreach($eleves as $eleve)
                                    <option value="{{ $eleve->id_eleve }}" 
                                        {{ $eleve->id_eleve == $evaluation->id_eleve ? 'selected' : '' }}>
                                        {{ $eleve->user->prenom }} {{ $eleve->user->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Matière -->
                        <div class="mb-3">
                            <label for="id_matiere" class="form-label"><i class="fas fa-book"></i> Matière</label>
                            <select name="id_matiere" id="id_matiere" class="form-select shadow-sm" required>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id_matiere }}" 
                                        {{ $matiere->id_matiere == $evaluation->id_matiere ? 'selected' : '' }}>
                                        {{ $matiere->intitule }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type d'évaluation -->
                        <div class="mb-3">
                            <label for="type" class="form-label"><i class="fas fa-clipboard-list"></i> Type d'évaluation</label>
                            <select name="type" id="type" class="form-select shadow-sm" required>
                                <option value="DS" {{ $evaluation->type == 'DS' ? 'selected' : '' }}>Devoir Surveillé (DS)</option>
                                <option value="Exam" {{ $evaluation->type == 'Exam' ? 'selected' : '' }}>Examen</option>
                                <option value="Rattrapage" {{ $evaluation->type == 'Rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                            </select>
                        </div>

                        <!-- Date de l'évaluation -->
                        <div class="mb-3">
                            <label for="date_evaluation" class="form-label"><i class="fas fa-calendar-alt"></i> Date de l'évaluation</label>
                            <input type="date" name="date_evaluation" class="form-control shadow-sm" value="{{ $evaluation->date_evaluation }}" required>
                        </div>

                        <!-- Note -->
                        <div class="mb-3">
                            <label for="note" class="form-label"><i class="fas fa-star"></i> Note</label>
                            <input type="number" name="note" class="form-control shadow-sm" min="0" max="20" value="{{ $evaluation->note }}" required>
                        </div>

                        <!-- Présence -->
                        <div class="mb-3">
                            <label for="presence" class="form-label"><i class="fas fa-user-check"></i> Présence</label>
                            <select name="presence" id="presence" class="form-select shadow-sm" required>
                                <option value="Présent" {{ $evaluation->presence == 'Présent' ? 'selected' : '' }}>Présent</option>
                                <option value="Absent" {{ $evaluation->presence == 'Absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour l'évaluation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
