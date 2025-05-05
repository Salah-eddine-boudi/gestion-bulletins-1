<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Assurer;
use App\Models\Professeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EvaluationController extends Controller
{
    /* -------------------------------------------------------------------------
     |  Vérifications d’accès
     | ---------------------------------------------------------------------- */

    /**
     * Vérifie qu’un utilisateur authentifié possède un rôle autorisé.
     */
    private function checkAuthorized(): void
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'directeur', 'professeur', 'eleve'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Interdit l’accès aux élèves pour certaines actions.
     */
    private function checkNotStudent(): void
    {
        if (auth()->user()->role === 'eleve') {
            abort(403, 'Accès non autorisé.');
        }
    }

    /* -------------------------------------------------------------------------
     |  Évaluations classiques
     | ---------------------------------------------------------------------- */

    /**
     * Index – liste paginée des évaluations (cache 10 s).
     */
    public function index(Request $request)
    {
        $this->checkAuthorized();

        $user      = auth()->user();
        $cacheKey  = 'evaluations_index_'.$user->id.'_'.$request->query('page', 1)
                   .'_'.$request->query('niveau').'_'.$request->query('id_matiere');

        $evaluations = Cache::remember($cacheKey, 36000, function () use ($user, $request) {
            $query = Evaluation::with(['eleve.user', 'matiere']);

            // Filtrage selon le rôle
            if ($user->role === 'professeur') {
                $prof = Professeur::where('id_user', $user->id)->firstOrFail();
                $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                       ->pluck('id_matiere');
                $query->whereIn('id_matiere', $matieresProf);
            } elseif ($user->role === 'eleve') {
                $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
                $query->where('id_eleve', $eleve->id_eleve);
            }

            // Filtres supplémentaires
            if ($request->filled('niveau')) {
                $query->whereHas('eleve', fn ($q) => $q->where('niveau_scolaire', $request->niveau));
            }
            if ($request->filled('id_matiere')) {
                $query->where('id_matiere', $request->id_matiere);
            }

            return $query->paginate(10);
        });

        $niveaux  = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];
        $matieres = $user->role === 'professeur'
            ? Matiere::whereIn('id_matiere',
                  Assurer::where('id_prof',
                      Professeur::where('id_user', $user->id)->firstOrFail()->id_prof
                  )->pluck('id_matiere')
              )->get()
            : Matiere::all();

        return view('evaluations.index', compact('evaluations', 'niveaux', 'matieres'));
    }

    /**
     * Renvoie en JSON les élèves filtrés (Ajax).
     */
    public function getElevesByFilters(Request $request)
    {
        $this->checkAuthorized();

        $eleves = Eleve::with('user')
            ->when($request->filled('niveau'),
                fn ($q) => $q->where('niveau_scolaire', $request->niveau))
            ->when($request->filled('id_matiere'), function ($q) use ($request) {
                $q->whereHas('evaluations',
                    fn ($q2) => $q2->where('id_matiere', $request->id_matiere));
            })
            ->get();

        return response()->json($eleves);
    }

    /**
     * Formulaire de création d’une évaluation.
     */
    public function create(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $user     = auth()->user();
        $niveaux  = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];
        $matieres = $user->role === 'professeur'
            ? Matiere::whereIn('id_matiere',
                  Assurer::where('id_prof',
                      Professeur::where('id_user', $user->id)->firstOrFail()->id_prof
                  )->pluck('id_matiere')
              )->get()
            : Matiere::all();

        $eleves = Eleve::when($request->niveau,
                    fn ($q) => $q->where('niveau_scolaire', $request->niveau))
                ->when($request->id_matiere, function ($q) use ($request) {
                    $q->whereHas('matieres',
                        fn ($q2) => $q2->where('matieres.id_matiere', $request->id_matiere));
                })
                ->get();

        return view('evaluations.create', compact('eleves', 'matieres', 'niveaux'));
    }

    /**
     * Enregistrement d’une évaluation.
     */
    public function store(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $user = auth()->user();

        $request->validate([
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'type'            => 'required|string',
            'date_evaluation' => 'required|date',
            'notes'           => 'required|array',
        ]);

        // Vérification matière ↔ professeur
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            if (!Assurer::where('id_prof', $prof->id_prof)
                        ->where('id_matiere', $request->id_matiere)
                        ->exists()) {
                abort(403, 'Vous n\'enseignez pas cette matière.');
            }
        }

        foreach ($request->notes as $eleve_id => $note_data) {
            Evaluation::create([
                'id_eleve'        => $eleve_id,
                'id_matiere'      => $request->id_matiere,
                'type'            => $request->type,
                'date_evaluation' => $request->date_evaluation,
                'note'            => $note_data['presence'] === 'Absent' ? null : $note_data['note'],
                'presence'        => $note_data['presence'],
            ]);
        }

        // Invalidation du cache (tous les rôles concernés)
        Cache::tags('evaluations')->flush();

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluations ajoutées avec succès.');
    }

    /**
     * Affichage d’une évaluation.
     */
    public function show($id)
    {
        $this->checkAuthorized();

        $evaluation = Evaluation::with(['eleve.user', 'matiere'])->findOrFail($id);
        $user       = auth()->user();

        // Autorisation spécifique
        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            if (!Assurer::where('id_prof', $prof->id_prof)
                        ->where('id_matiere', $evaluation->id_matiere)
                        ->exists()) {
                abort(403, 'Accès non autorisé.');
            }
        } elseif ($user->role === 'eleve') {
            $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
            if ($evaluation->id_eleve !== $eleve->id_eleve) {
                abort(403, 'Accès non autorisé.');
            }
        }

        return view('evaluations.show', compact('evaluation'));
    }

    /**
     * Formulaire d’édition.
     */
    public function edit($id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $evaluation = Evaluation::findOrFail($id);
        $user       = auth()->user();

        if ($user->role === 'professeur') {
            $prof = Professeur::where('id_user', $user->id)->firstOrFail();
            if (!Assurer::where('id_prof', $prof->id_prof)
                        ->where('id_matiere', $evaluation->id_matiere)
                        ->exists()) {
                abort(403, 'Accès non autorisé.');
            }
        }

        $eleves   = Eleve::all();
        $matieres = Matiere::all();

        return view('evaluations.edit', compact('evaluation', 'eleves', 'matieres'));
    }

    /**
     * Mise à jour d’une évaluation.
     */
    public function update(Request $request, $id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $request->validate([
            'id_eleve'        => 'required|exists:eleves,id_eleve',
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'type'            => 'required|string',
            'date_evaluation' => 'required|date',
            'note'            => 'nullable|numeric|min:0|max:20',
            'presence'        => 'required|string',
        ]);

        $evaluation = Evaluation::findOrFail($id);
        $evaluation->update($request->all());

        Cache::tags('evaluations')->flush();

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluation mise à jour avec succès.');
    }

    /**
     * Suppression d’une évaluation.
     */
    public function destroy($id)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        Evaluation::findOrFail($id)->delete();

        Cache::tags('evaluations')->flush();

        return redirect()->route('evaluations.index')
                         ->with('success', 'Évaluation supprimée avec succès.');
    }

    /* -------------------------------------------------------------------------
     |  Rattrapage
     | ---------------------------------------------------------------------- */

    /**
     * Liste des rattrapages (cache 10 s).
     */
    public function rattrapageView(Request $request)
    {
        $this->checkAuthorized();
        $user     = auth()->user();
        $cacheKey = 'evaluations_ratt_'.$user->id.'_'.$request->query('page', 1)
                   .'_'.$request->query('niveau').'_'.$request->query('id_matiere');

        $evaluations = Cache::remember($cacheKey, 10, function () use ($user, $request) {
            $query = Evaluation::with(['eleve.user', 'matiere'])
                               ->where('type', 'Rattrapage');

            if ($user->role === 'professeur') {
                $prof = Professeur::where('id_user', $user->id)->firstOrFail();
                $matieresProf = Assurer::where('id_prof', $prof->id_prof)
                                       ->pluck('id_matiere');
                $query->whereIn('id_matiere', $matieresProf);
            } elseif ($user->role === 'eleve') {
                $eleve = Eleve::where('id_user', $user->id)->firstOrFail();
                $query->where('id_eleve', $eleve->id_eleve);
            }

            if ($request->filled('niveau')) {
                $query->whereHas('eleve', fn ($q) => $q->where('niveau_scolaire', $request->niveau));
            }
            if ($request->filled('id_matiere')) {
                $query->where('id_matiere', $request->id_matiere);
            }

            return $query->paginate(10);
        });

        $niveaux = ['JM1', 'JM2', 'JM3-ISEN', 'JM3-HEI'];
        $matieres = $user->role === 'professeur'
            ? Matiere::whereIn('id_matiere',
                  Assurer::where('id_prof',
                      Professeur::where('id_user', $user->id)->firstOrFail()->id_prof
                  )->pluck('id_matiere')
              )->get()
            : Matiere::all();

        return view('evaluations.rattrapage', compact('evaluations', 'niveaux', 'matieres'));
    }

    /**
     * Formulaire de création de rattrapage.
     */
    public function createRatt()
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $user     = auth()->user();
        $niveaux  = ['JM1', 'JM2', 'JM3', 'HEI', 'ISEN'];
        $matieres = $user->role === 'professeur'
            ? Matiere::whereIn('id_matiere',
                  Assurer::where('id_prof',
                      Professeur::where('id_user', $user->id)->firstOrFail()->id_prof
                  )->pluck('id_matiere')
              )->get()
            : Matiere::all();

        $eleves = Eleve::with(['user', 'evaluations' => fn ($q) => $q->where('type', 'Final')])->get();

        $eleves->map(function ($eleve) {
            $final = $eleve->evaluations->first();
            $eleve->evaluation_examen = $final ? ($final->presence === 'Absent' ? 'Absent' : $final->note) : null;
            return $eleve;
        });

        return view('evaluations.create_rattrapage', compact('niveaux', 'matieres', 'eleves'));
    }

    /**
     * Stockage groupé des rattrapages.
     */
    public function storeRattGroup(Request $request)
    {
        $this->checkAuthorized();
        $this->checkNotStudent();

        $request->validate([
            'id_matiere'      => 'required|exists:matieres,id_matiere',
            'date_evaluation' => 'required|date',
            'notes'           => 'required|array',
        ]);

        foreach ($request->notes as $idEleve => $noteData) {
            $note = $noteData['presence'] === 'Absent' ? null : $noteData['note'];

            Evaluation::updateOrCreate(
                [
                    'id_eleve'   => $idEleve,
                    'id_matiere' => $request->id_matiere,
                    'type'       => 'Rattrapage',
                ],
                [
                    'date_evaluation' => $request->date_evaluation,
                    'note'            => $note,
                    'presence'        => $noteData['presence'],
                ]
            );
        }

        Cache::tags('evaluations')->flush();

        return redirect()->route('evaluations.rattrapage')
                         ->with('success', 'Rattrapages enregistrés avec succès.');
    }
}
