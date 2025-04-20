@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Saisie des évaluations</h3>

    <!-- Formulaire de sélection du niveau -->
    <form id="filterForm">
        @csrf
        <div class="mb-3">
            <label for="niveau" class="form-label">Niveau</label>
            <select name="niveau" id="niveau" class="form-control">
                <option value="">Sélectionner un niveau</option>
                @foreach($niveaux as $niveau)
                    <option value="{{ $niveau }}">{{ $niveau }}</option>
                @endforeach
            </select>
        </div>
        <p id="loadingMessage" class="text-muted" style="display: none;">Chargement des élèves...</p>
    </form>

    <!-- Formulaire des évaluations -->
    <form action="{{ route('evaluations.store') }}" method="POST" id="evaluationForm" style="display: none;">
        @csrf

        <!-- Sélectionner la matière -->
        <div class="mb-3">
            <label for="id_matiere" class="form-label">Matière</label>
            <select name="id_matiere" id="id_matiere" class="form-control" required>
                <option value="">Choisir une matière</option>
                @foreach ($matieres as $matiere)
                    <option value="{{ $matiere->id_matiere }}">{{ $matiere->intitule }}</option>
                @endforeach
            </select>
        </div>

        <!-- Table des élèves -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nom de l'élève</th>
                    <th>Note</th>
                    <th>Présence</th>
                </tr>
            </thead>
            <tbody id="eleveTableBody">
                <tr>
                    <td colspan="3" class="text-center">Sélectionnez un niveau</td>
                </tr>
            </tbody>
        </table>

        <!-- Type d'évaluation -->
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-control" required>
                <option value="DS">DS</option>
                <option value="Exam">Examen</option>
                <option value="Rattrapage">Rattrapage</option>
            </select>
        </div>

        <!-- Date -->
        <div class="mb-3">
            <label for="date_evaluation" class="form-label">Date</label>
            <input type="date" name="date_evaluation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const niveauSelect = document.getElementById("niveau");
    const eleveTableBody = document.getElementById("eleveTableBody");
    const evaluationForm = document.getElementById("evaluationForm");
    const loadingMessage = document.getElementById("loadingMessage");

    function fetchEleves() {
        const niveau = niveauSelect.value;
        let url = `{{ route('evaluations.getEleves') }}`;
        if (niveau) url += `?niveau=${niveau}`;

        loadingMessage.style.display = "block";
        eleveTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Chargement...</td></tr>';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                loadingMessage.style.display = "none";
                eleveTableBody.innerHTML = "";

                if (data.length === 0) {
                    eleveTableBody.innerHTML = '<tr><td colspan="3" class="text-center">Aucun élève</td></tr>';
                    evaluationForm.style.display = "none";
                } else {
                    evaluationForm.style.display = "block";
                    data.forEach(eleve => {
                        eleveTableBody.innerHTML += `
                            <tr>
                                <td>${eleve.user.prenom} ${eleve.user.nom}</td>
                                <td><input type="number" name="notes[${eleve.id_eleve}][note]" class="form-control note" min="0" max="20" required></td>
                                <td>
                                    <select name="notes[${eleve.id_eleve}][presence]" class="form-control" onchange="toggleNoteField(this)">
                                        <option value="Présent">Présent</option>
                                        <option value="Absent">Absent</option>
                                    </select>
                                </td>
                            </tr>`;
                    });
                }
            })
            .catch(() => {
                loadingMessage.style.display = "none";
                eleveTableBody.innerHTML = '<tr><td colspan="3" class="text-danger text-center">Erreur de chargement</td></tr>';
            });
    }

    niveauSelect.addEventListener("change", fetchEleves);

    window.toggleNoteField = function (selectElement) {
        const noteInput = selectElement.closest("tr").querySelector(".note");
        noteInput.disabled = selectElement.value === "Absent";
        if (noteInput.disabled) noteInput.value = "";
    };
});
</script>
@endsection
