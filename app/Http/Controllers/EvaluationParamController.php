<?php

namespace App\Http\Controllers;

use App\Models\ParametrageEvaluation;
use App\Models\Professeur;
use App\Models\Assurer;
use App\Models\Matiere;
use Illuminate\Http\Request;

class EvaluationParamController extends Controller
{
    /**
     * Affiche la liste du paramétrage des évaluations.
     * On récupère tous les paramètres avec leurs relations (professeur et matière)
     */
    public function index()
    {
        $params = ParametrageEvaluation::with(['professeur', 'matiere'])->get();
        return view('evaluation_params.index', compact('params'));
    }

    /**
     * Affiche le formulaire de création d’un nouveau paramétrage.
     * Seul un utilisateur avec le rôle "professeur" peut accéder à ce formulaire.
     * Le contrôleur récupère d'abord le profil du professeur (via l'utilisateur connecté)
     * et ensuite les matières qu'il enseigne via la table Assurer.
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role !== 'professeur') {
            abort(403, 'Accès réservé aux professeurs.');
        }

        // Récupérer le profil professeur à partir de l'utilisateur authentifié
        $prof = Professeur::where('id_user', $user->id)->firstOrFail();

        // Récupérer les IDs des matières que ce professeur enseigne via la table Assurer
        $matieresIds = Assurer::where('id_prof', $prof->id_prof)
                              ->pluck('id_matiere');
        // Récupérer les matières correspondantes
        $matieres = Matiere::whereIn('id_matiere', $matieresIds)->get();

        return view('evaluation_params.create', compact('prof', 'matieres'));
    }

    /**
     * Stocke le paramétrage soumis via le formulaire.
     * On valide que pour l'ensemble des lignes, la somme des pourcentages est égale à 100%.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|integer|min:1', // Nombre total d'évaluations
            'id_matiere' => 'required|exists:matieres,id_matiere',
            'evaluations' => 'required|array|min:1',
            'evaluations.*.type' => 'required|string|in:DS,EXAM,RATTRAPAGE',
            'evaluations.*.pourcentage' => 'required|numeric|min:0|max:100',
            'evaluations.*.nombre_evaluations' => 'required|integer|min:1',
        ]);

        // Vérifier que la somme des pourcentages de toutes les lignes est égale à 100%
        $sum = 0;
        foreach ($validated['evaluations'] as $eval) {
            $sum += $eval['pourcentage'];
        }
        if (abs($sum - 100) > 0.01) {
            return redirect()->back()
                ->withErrors(['La somme de tous les pourcentages doit être égale à 100%.'])
                ->withInput();
        }

        // Récupérer l'ID de la matière sélectionnée
        $id_matiere = $validated['id_matiere'];
        // Récupérer l'identifiant du professeur à partir de l'utilisateur authentifié
        $profId = auth()->user()->professeur->id_prof;

        // Enregistrer chaque paramétrage (chaque ligne)
        foreach ($validated['evaluations'] as $evalData) {
            ParametrageEvaluation::create([
                'id_professeur'      => $profId,
                'id_matiere'         => $id_matiere,
                'type'               => $evalData['type'],
                'pourcentage'        => $evalData['pourcentage'],
                'nombre_evaluations' => $evalData['nombre_evaluations'],
            ]);
        }

        return redirect()->route('evaluation-params.index')
                         ->with('success', 'Paramétrage enregistré avec succès.');
    }

    // Les méthodes show, edit, update et destroy pourront être ajoutées ultérieurement si besoin.
}
