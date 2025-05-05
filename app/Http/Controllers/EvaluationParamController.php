<?php

namespace App\Http\Controllers;

use App\Models\ParametrageEvaluation;
use App\Models\Professeur;
use App\Models\Assurer;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class EvaluationParamController extends Controller
{
    /* =========================================================
     |  LISTE
     |=========================================================*/
    public function index()
    {
        // On charge la relation user du professeur + matière pour l’affichage.
        $params = ParametrageEvaluation::with(['professeur.user', 'matiere'])
                    ->orderByDesc('created_at')
                    ->paginate(15);

        return view('evaluation_params.index', compact('params'));
    }

    /* =========================================================
     |  FORMULAIRE DE CRÉATION
     |=========================================================*/
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'professeur') {
            abort(403, 'Accès réservé aux professeurs.');
        }

        $prof = Professeur::where('id_user', $user->id)->firstOrFail();

        // Matières assurées par ce professeur
        $matiereIds = Assurer::where('id_prof', $prof->id_prof)->pluck('id_matiere');
        $matieres   = Matiere::whereIn('id_matiere', $matiereIds)->get();

        return view('evaluation_params.create', compact('prof', 'matieres'));
    }

    /* =========================================================
     |  ENREGISTREMENT
     |=========================================================*/
    public function store(Request $request): RedirectResponse
    {
        /* ---------------- Validation ---------------- */
        $validated = $request->validate([
            'id_matiere'               => 'required|exists:matieres,id_matiere',
            'total'                    => 'required|integer|min:0|max:7',
            'override_rattrapage'      => 'sometimes|boolean',
            'pourcentage_rattrapage'   => 'required|numeric|min:0|max:100',
            'pourcentage_examen_final' => 'required|numeric|min:0|max:100',

            'evaluations'                     => 'required|array|min:1',
            'evaluations.*.type'              => 'required|string|in:DS,EXAM',
            'evaluations.*.pourcentage'       => 'required|numeric|min:0|max:100',
            'evaluations.*.nombre_evaluations'=> 'required|integer|min:1',
        ]);

        // Vérifier que DS+EXAM totalisent 100 %
        $sumDSPct = collect($validated['evaluations'])
                      ->sum('pourcentage');
        if (abs($sumDSPct - 100) > 0.01) {
            return back()->withErrors([
                'evaluations' => 'La somme des pourcentages DS + Examen final doit être 100 %.',
            ])->withInput();
        }

        // Vérifier que Rattrapage + Examen final = 100 %
        $sumRattr = $validated['pourcentage_rattrapage'] +
                    $validated['pourcentage_examen_final'];
        if (abs($sumRattr - 100) > 0.01) {
            return back()->withErrors([
                'pourcentage_rattrapage'   => 'Le total rattrapage + examen final doit être 100 %.',
                'pourcentage_examen_final' => 'Le total rattrapage + examen final doit être 100 %.',
            ])->withInput();
        }

        /* ---------------- Création -------------------*/
        $profId       = auth()->user()->professeur->id_prof;
        $flagOverride = $request->boolean('override_rattrapage', false);

        DB::transaction(function () use ($validated, $profId, $flagOverride) {
            foreach ($validated['evaluations'] as $ev) {
                ParametrageEvaluation::create([
                    'id_professeur'            => $profId,
                    'id_matiere'               => $validated['id_matiere'],
                    'type'                     => $ev['type'],
                    'pourcentage'              => $ev['pourcentage'],
                    'nombre_evaluations'       => $ev['nombre_evaluations'],
                    'override_rattrapage'      => $flagOverride,
                    'pourcentage_rattrapage'   => $validated['pourcentage_rattrapage'],
                    'pourcentage_examen_final' => $validated['pourcentage_examen_final'],
                ]);
            }
        });

        return redirect()
            ->route('evaluation-params.index')
            ->with('success', 'Paramétrage enregistré avec succès.');
    }

    /* =========================================================
     |  FORMULAIRE D'ÉDITION
     |=========================================================*/
    public function edit($id)
    {
        $param = ParametrageEvaluation::findOrFail($id);
        return view('evaluation_params.edit', compact('param'));
    }

    /* =========================================================
     |  MISE À JOUR
     |=========================================================*/
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'id_professeur'      => 'required|integer|exists:professeurs,id_prof',
            'id_matiere'         => 'required|integer|exists:matieres,id_matiere',
            'type'               => 'required|in:DS,EXAM,RATTRAPAGE',
            'nombre_evaluations' => 'required|integer|min:1',
            'pourcentage'        => 'required|numeric|min:0|max:100',
        ]);

        $param = ParametrageEvaluation::findOrFail($id);
        $param->update($validated);

        return redirect()
            ->route('evaluation-params.index')
            ->with('success', 'Paramétrage mis à jour avec succès.');
    }

    /* =========================================================
     |  SUPPRESSION
     |=========================================================*/
    public function destroy(ParametrageEvaluation $evaluation): RedirectResponse
    {
        $evaluation->delete();

        return redirect()
            ->route('evaluation-params.index')
            ->with('success', 'Paramétrage supprimé.');
    }
}
