<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Assurer;
use App\Models\Professeur;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /**
     * Vérifie que l'utilisateur est authentifié et qu'il a un rôle autorisé (admin, directeur, professeur ou élève).
     */
    private function checkAuthorized()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'directeur', 'professeur', 'eleve'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Vérifie que l'utilisateur connecté n'est pas un élève. 
     * Utilisé sur les actions de création, modification ou suppression d'évaluations.
     */
    private function checkNotStudent()
    {
        $user = auth()->user();
        if ($user->role === 'eleve') {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Liste des évaluations (index général)
     */
    public function index(Request $request)
    {
        $this->checkAuthorized();
        $user = auth()->user();
        $query = Evaluation::with(['eleve.user', 'matiere']);

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            // Filtrer les évaluations par les matières que le prof enseigne
            $query->whereIn('id_matiere', $matieresProf);
        } elseif ($user->role === 'eleve') {
            $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
            $query->where('id_eleve', $eleve->id_eleve);
        }

        if ($request->filled('niveau')) {
            $query->whereHas('eleve', function ($q) use ($request) {
                $q->where('niveau_scolaire', $request->niveau);
            });
        }
        if ($request->filled('id_matiere')) {
            $query->where('id_matiere', $request->id_matiere);
        }

        $evaluations = $query->paginate(10);
        $niveaux = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];

        // Pour les filtres, si professeur, on limite la liste des matières aux matières qu'il enseigne
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            $matieres = Matiere::whereIn('id_matiere', $matieresProf)->get();
        } else {
            $matieres = Matiere::all();
        }

        return view('evaluations.index', compact('evaluations', 'niveaux', 'matieres'));
    }

    /**
     * Récupère les élèves pour un filtre Ajax (évaluations classiques)
     */
    public function getElevesByFilters(Request $request)
    {
        $this->checkAuthorized();

        $query = Eleve::with('user');

        if ($request->filled('niveau')) {
            $query->where('niveau_scolaire', $request->niveau);
        }
        if ($request->filled('id_matiere')) {
            $query->whereHas('evaluations', function ($q) use ($request) {
                $q->where('id_matiere', $request->id_matiere);
            });
        }

        return response()->json($query->get());
    }

    /**
     * Récupère les élèves pour le rattrapage (filtre Ajax)
     */
    public function getElevesPourRattrapage(Request $request)
    {
        $this->checkAuthorized();

        $query = Eleve::with('user');

        if ($request->filled('niveau')) {
            $query->where('niveau_scolaire', $request->niveau);
        }
        if ($request->filled('id_matiere')) {
            $query->whereHas('evaluations', function ($q) use ($request) {
                $q->where('id_matiere', $request->id_matiere)
                  ->where('type', 'Rattrapage');
            });
        }

        return response()->json($query->get());
    }

    /**
     * Formulaire de création d'une évaluation classique
     */
    public function create(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $niveaux = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];

        // Si professeur, limiter les matières aux matières qu'il enseigne
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            $matieres = Matiere::whereIn('id_matiere', $matieresProf)->get();
        } else {
            $matieres = Matiere::all();
        }

        // Récupérer les élèves éventuellement filtrés
        $eleves = Eleve::when($request->niveau, fn($q) => $q->where('niveau_scolaire', $request->niveau))
            ->when($request->id_matiere, fn($q) => $q->whereHas('matieres', fn($q2) => $q2->where('matieres.id_matiere', $request->id_matiere)))
            ->get();

        return view('evaluations.create', compact('eleves', 'matieres', 'niveaux'));
    }

    /**
     * Enregistrement d'une évaluation classique
     */
    public function store(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();

        $request->validate([
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'type'            => 'required|string',
            'date_evaluation' => 'required|date',
            'notes'           => 'required|array',
        ]);

        // Si professeur, vérifier qu'il enseigne bien la matière
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            if (!in_array($request->id_matiere, $matieresProf)) {
                abort(403, 'Vous n\'enseignez pas cette matière.');
            }
        }

        foreach ($request->notes as $eleve_id => $note_data) {
            $note = ($note_data['presence'] === 'Absent') ? null : $note_data['note'];
            Evaluation::create([
                'id_eleve'        => $eleve_id,
                'id_matiere'      => $request->id_matiere,
                'type'            => $request->type,
                'date_evaluation' => $request->date_evaluation,
                'note'            => $note,
                'presence'        => $note_data['presence'],
            ]);
        }

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluations ajoutées avec succès.');
    }

    /**
     * Affichage d'une évaluation
     */
    public function show($id)
    {
        $this->checkAuthorized();
        $evaluation = Evaluation::with(['eleve.user', 'matiere'])->findOrFail($id);
        $user = auth()->user();

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();

            if (!in_array($evaluation->id_matiere, $matieresProf)) {
                abort(403, 'Accès non autorisé.');
            }
        } elseif ($user->role === 'eleve') {
            $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
            if ($evaluation->id_eleve != $eleve->id_eleve) {
                abort(403, 'Accès non autorisé.');
            }
        }

        return view('evaluations.show', compact('evaluation'));
    }

    /**
     * Formulaire d'édition d'une évaluation
     */
    public function edit($id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $evaluation = Evaluation::findOrFail($id);

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            if (!in_array($evaluation->id_matiere, $matieresProf)) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $eleves = Eleve::all();
        $matieres = Matiere::all();

        return view('evaluations.edit', compact('evaluation', 'eleves', 'matieres'));
    }

    /**
     * Mise à jour d'une évaluation
     */
    public function update(Request $request, $id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $request->validate([
            'id_eleve'        => 'required|exists:eleves,id_eleve',
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'type'            => 'required|string',
            'date_evaluation' => 'required|date',
            'note'            => 'nullable|numeric|min:0|max:20',
            'presence'        => 'required|string',
        ]);

        $evaluation = Evaluation::findOrFail($id);

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            if (!in_array($evaluation->id_matiere, $matieresProf)) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $evaluation->update($request->all());

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluation mise à jour avec succès.');
    }

    /**
     * Suppression d'une évaluation
     */
    public function destroy($id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $evaluation = Evaluation::findOrFail($id);

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            if (!in_array($evaluation->id_matiere, $matieresProf)) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $evaluation->delete();

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluation supprimée avec succès.');
    }

    /*-----------------------------------------
     * PARTIE RATTRAPAGE
     *----------------------------------------*/

    /**
     * Affiche la liste des évaluations de rattrapage
     */
    public function rattrapageView(Request $request)
    {
        $this->checkAuthorized();
        $user = auth()->user();

        $query = Evaluation::with(['eleve.user', 'matiere'])
                           ->where('type', 'Rattrapage');

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            $query->whereIn('id_matiere', $matieresProf);
        } elseif ($user->role === 'eleve') {
            $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
            $query->where('id_eleve', $eleve->id_eleve);
        }

        if ($request->filled('niveau')) {
            $query->whereHas('eleve', function($q) use ($request) {
                $q->where('niveau_scolaire', $request->niveau);
            });
        }
        if ($request->filled('id_matiere')) {
            $query->where('id_matiere', $request->id_matiere);
        }

        $evaluations = $query->paginate(10);
        $niveaux = ['JM1', 'JM2', 'JM3-ISEN', 'JM3-HEI'];

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            $matieres = Matiere::whereIn('id_matiere', $matieresProf)->get();
        } else {
            $matieres = Matiere::all();
        }

        return view('evaluations.rattrapage', compact('evaluations', 'niveaux', 'matieres'));
    }

    /**
     * Formulaire de création pour le rattrapage
     */
    public function createRatt()
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $niveaux = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            $matieres = Matiere::whereIn('id_matiere', $matieresProf)->get();
        } else {
            $matieres = Matiere::all();
        }

        $eleves = Eleve::with(['user', 'evaluations' => function($query) {
            $query->where('type', 'Final');
        }])->get();

        // Pour chaque élève, déterminer la note finale de l'examen s'il existe
        $eleves->map(function($eleve) {
            $finalEval = $eleve->evaluations->first();
            $eleve->evaluation_examen = $finalEval
                ? ($finalEval->presence === 'Absent' ? 'Absent' : $finalEval->note)
                : null;
            return $eleve;
        });

        return view('evaluations.create_rattrapage', compact('niveaux', 'matieres', 'eleves'));
    }

    /**
     * Enregistrement groupé pour le rattrapage
     */
    public function storeRattGroup(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent(); // Blocage pour les élèves

        $user = auth()->user();
        $request->validate([
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'date_evaluation' => 'required|date',
            'notes'           => 'required|array',
        ]);

        // Vérifier si le professeur enseigne bien la matière
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                   ->pluck('id_matiere')
                                   ->toArray();
            if (!in_array($request->id_matiere, $matieresProf)) {
                abort(403, 'Vous n\'enseignez pas cette matière.');
            }
        }

        foreach ($request->notes as $idEleve => $noteData) {
            $note = ($noteData['presence'] === 'Absent') ? null : $noteData['note'];
            
            // Rechercher une évaluation existante de type "Rattrapage"
            $evaluation = Evaluation::where('id_eleve', $idEleve)
                                    ->where('id_matiere', $request->id_matiere)
                                    ->where('type', 'Rattrapage')
                                    ->first();
            
            if ($evaluation) {
                // Mettre à jour l'évaluation existante
                $evaluation->update([
                    'date_evaluation' => $request->date_evaluation,
                    'note'            => $note,
                    'presence'        => $noteData['presence'],
                ]);
            } else {
                // Créer une nouvelle évaluation
                Evaluation::create([
                    'id_eleve'        => $idEleve,
                    'id_matiere'      => $request->id_matiere,
                    'type'            => 'Rattrapage',
                    'date_evaluation' => $request->date_evaluation,
                    'note'            => $note,
                    'presence'        => $noteData['presence'],
                ]);
            }
        }
        
        return redirect()->route('evaluations.rattrapage')
                         ->with('success', 'Rattrapages enregistrés avec succès.');
    }
}
