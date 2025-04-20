@extends('layouts.app')

@section('content')
<div id="app" class="container">
    <h3 class="mb-4">Saisie des notes de rattrapage</h3>
    
    <!-- Sélection du niveau -->
    <div class="mb-3">
        <label for="niveau" class="form-label">Niveau</label>
        <select v-model="selectedNiveau" class="form-control" @change="fetchEleves">
            <option value="">Sélectionner un niveau</option>
            @foreach($niveaux as $niveau)
                <option value="{{ $niveau }}">{{ $niveau }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Recherche intégrée pour filtrer la liste des étudiants -->
    <div class="mb-3">
        <input type="text" v-model="searchQuery" class="form-control" placeholder="Rechercher un élève...">
    </div>
    
    <!-- Saisie globale pour appliquer une note identique -->
    <div class="mb-3 d-flex">
        <input type="number" v-model="globalNote" class="form-control" step="0.01" min="0" max="20" placeholder="Note à appliquer à tous">
        <button type="button" class="btn btn-secondary ms-2" @click="applyGlobalNote">Appliquer à tous</button>
    </div>
    
    <!-- Formulaire de validation, visible uniquement s'il y a au moins un étudiant éligible -->
    <!-- Attention: Assurez-vous que le nom de la route correspond à votre déclaration de route -->
    <form action="{{ route('evaluations.storeRattrapage') }}" method="POST" @submit.prevent="submitForm" v-if="eleves.length > 0">
        @csrf
        
        <!-- Sélection de la matière -->
        <div class="mb-3">
            <label for="id_matiere" class="form-label">Matière</label>
            <select name="id_matiere" id="id_matiere" class="form-control" required>
                <option value="">Sélectionnez une matière</option>
                @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id_matiere }}">{{ $matiere->intitule }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Date de saisie -->
        <div class="mb-3">
            <label for="date_evaluation" class="form-label">Date de saisie</label>
            <input type="date" name="date_evaluation" class="form-control" required>
        </div>
        
        <!-- Tableau des étudiants éligibles au rattrapage -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'élève</th>
                    <th>Note d'examen</th>
                    <th>Présence</th>
                    <th>Note de rattrapage</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(eleve, index) in filteredEleves" :key="eleve.id_eleve">
                    <td>@{{ eleve.user.prenom }} @{{ eleve.user.nom }}</td>
                    <td>
                        @{{ (eleve.evaluation_examen || '').toUpperCase() === 'ABSENT' ? 'Absent' : eleve.evaluation_examen }}
                    </td>
                    <td>
                        <select v-model="eleve.presence" class="form-control" @change="toggleRattNoteField(eleve)">
                            <option value="Présent">Présent</option>
                            <option value="Absent">Absent</option>
                        </select>
                        <!-- Champ caché pour transmettre la présence -->
                        <input type="hidden"
                               :name="`notes[${eleve.id_eleve}][presence]`"
                               :value="eleve.presence">
                    </td>
                    <td>
                        <input type="number" v-model.number="eleve.note" class="form-control"
                               step="0.01" min="0" max="20" placeholder="Note de ratt"
                               :disabled="eleve.presence === 'Absent'" required>
                        <!-- Champ caché pour transmettre la note (on transmet une chaîne vide si absent) -->
                        <input type="hidden"
                               :name="`notes[${eleve.id_eleve}][note]`"
                               :value="eleve.presence === 'Absent' ? '' : eleve.note">
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Enregistrer les notes de rattrapage</button>
    </form>
    <p v-else class="text-center">Aucun élève éligible au rattrapage pour ce niveau.</p>
</div>

<!-- Inclusion de Vue.js depuis un CDN -->
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
new Vue({
    el: '#app',
    data: {
        selectedNiveau: '',
        eleves: [],
        searchQuery: '',
        globalNote: ''
    },
    computed: {
        filteredEleves() {
            if (!this.searchQuery) return this.eleves;
            return this.eleves.filter(eleve => {
                const nom = eleve.user.prenom + ' ' + eleve.user.nom;
                return nom.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        }
    },
    methods: {
        // Récupère la liste des élèves pour le niveau sélectionné
        fetchEleves() {
            if (!this.selectedNiveau) {
                this.eleves = [];
                return;
            }
            const url = `{{ route('evaluations.getRattrapage') }}?niveau=${this.selectedNiveau}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Filtrage automatique : ne garder que les élèves absents ou ayant une note inférieure à 10
                    this.eleves = data.filter(eleve => {
                        let evalEx = (eleve.evaluation_examen || '').toString().trim().toUpperCase();
                        return evalEx === 'ABSENT' || (!isNaN(evalEx) && Number(evalEx) < 10);
                    }).map(eleve => {
                        // Initialisation des champs de présence et de note pour le rattrapage
                        eleve.presence = (eleve.evaluation_examen || '').toString().trim().toUpperCase() === 'ABSENT' ? 'Absent' : 'Présent';
                        eleve.note = '';
                        return eleve;
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des élèves :', error));
        },
        // Applique une note globale à tous les élèves dont la présence est "Présent"
        applyGlobalNote() {
            if (this.globalNote === '' || isNaN(this.globalNote) || this.globalNote < 0 || this.globalNote > 20) {
                alert("Veuillez saisir une note valide comprise entre 0 et 20.");
                return;
            }
            this.eleves.forEach(eleve => {
                if (eleve.presence !== 'Absent') {
                    eleve.note = this.globalNote;
                }
            });
        },
        // Désactive la saisie de la note si l'étudiant est marqué absent
        toggleRattNoteField(eleve) {
            if (eleve.presence === 'Absent') {
                eleve.note = '';
            }
        },
        // Soumission du formulaire
        submitForm() {
            // La présence des champs cachés avec "name" permettra la transmission des données
            this.$el.querySelector('form').submit();
        }
    }
});
</script>
@endsection
