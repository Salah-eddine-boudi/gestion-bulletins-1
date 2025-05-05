@extends('layouts.app')

@section('content')
<div id="app" class="container py-4">
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
    
    <!-- Recherche intégrée -->
    <div class="mb-3">
        <input type="text" v-model="searchQuery" class="form-control" placeholder="Rechercher un élève...">
    </div>

    <!-- Note globale -->
    <div class="mb-3 d-flex">
        <input type="number" v-model="globalNote" class="form-control" step="0.01" min="0" max="20" placeholder="Note à appliquer à tous">
        <button type="button" class="btn btn-secondary ms-2" @click="applyGlobalNote">Appliquer à tous</button>
    </div>

    <!-- Formulaire d'enregistrement -->
    <form action="{{ route('evaluations.storeRattGroup') }}" method="POST" @submit.prevent="submitForm" v-if="eleves.length > 0">
        @csrf

        <div class="mb-3">
            <label for="id_matiere" class="form-label">Matière</label>
            <select name="id_matiere" id="id_matiere" class="form-control" required>
                <option value="">Sélectionnez une matière</option>
                @foreach($matieres as $matiere)
                    <option value="{{ $matiere->id_matiere }}">{{ $matiere->intitule }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date_evaluation" class="form-label">Date d'évaluation</label>
            <input type="date" name="date_evaluation" class="form-control" required>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'élève</th>
                    <th>Note Examen</th>
                    <th>Présence</th>
                    <th>Note de Rattrapage</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="eleve in filteredEleves" :key="eleve.id_eleve">
                    <td>@{{ eleve.user.prenom }} @{{ eleve.user.nom }}</td>
                    <td>@{{ eleve.evaluation_examen !== null ? (eleve.evaluation_examen === 'Absent' ? 'Absent' : eleve.evaluation_examen) : 'Non évalué' }}</td>
                    <td>
                        <select v-model="eleve.presence" class="form-control" :disabled="eleve.isLocked" @change="toggleRattNoteField(eleve)">
                            <option value="Présent">Présent</option>
                            <option value="Absent">Absent</option>
                        </select>
                        <input type="hidden" :name="`notes[${eleve.id_eleve}][presence]`" :value="eleve.presence">
                    </td>
                    <td>
                        <input type="number" 
                               v-model.number="eleve.note"
                               :disabled="eleve.isLocked || eleve.presence === 'Absent'" 
                               step="0.01" min="0" max="20" class="form-control" placeholder="Note" required>
                        <input type="hidden" :name="`notes[${eleve.id_eleve}][note]`" :value="eleve.presence === 'Absent' ? '' : eleve.note">
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary mt-3">Enregistrer les notes</button>
    </form>
    <p v-else class="text-center text-muted mt-4">Aucun élève éligible au rattrapage.</p>
</div>

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
                const nomComplet = eleve.user.prenom + ' ' + eleve.user.nom;
                return nomComplet.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        }
    },
    methods: {
        fetchEleves() {
            if (!this.selectedNiveau) {
                this.eleves = [];
                return;
            }
            fetch(`{{ route('evaluations.getRattrapage') }}?niveau=${this.selectedNiveau}`)
                .then(response => response.json())
                .then(data => {
                    this.eleves = data.map(eleve => {
                        let examNote = (eleve.evaluation_examen || '').toString().trim().toUpperCase();
                        let isLocked = false;
                        
                        if (examNote !== 'ABSENT' && !isNaN(examNote) && Number(examNote) >= 10) {
                            isLocked = true; // Si note >= 10, bloquer
                        }
                        if (eleve.has_rattrapage === true) {
                            isLocked = true; // Si déjà fait rattrapage, bloquer aussi
                        }

                        return {
                            ...eleve,
                            presence: examNote === 'ABSENT' ? 'Absent' : 'Présent',
                            note: '',
                            isLocked: isLocked
                        };
                    });
                })
                .catch(error => console.error('Erreur fetch élèves :', error));
        },
        applyGlobalNote() {
            if (this.globalNote === '' || isNaN(this.globalNote) || this.globalNote < 0 || this.globalNote > 20) {
                alert('Veuillez entrer une note valide entre 0 et 20.');
                return;
            }
            this.eleves.forEach(eleve => {
                if (!eleve.isLocked && eleve.presence !== 'Absent') {
                    eleve.note = this.globalNote;
                }
            });
        },
        toggleRattNoteField(eleve) {
            if (eleve.presence === 'Absent') {
                eleve.note = '';
            }
        },
        submitForm() {
            this.$el.querySelector('form').submit();
        }
    }
});
</script>
@endsection
