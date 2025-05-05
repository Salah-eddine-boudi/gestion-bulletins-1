<?php

namespace App\Http\Controllers;

use App\Models\UniteEnseignement;
use Illuminate\Http\Request;

class UniteEnseignementController extends Controller
{
    /**
     * Vérifie que l'utilisateur est autorisé (admin ou directeur).
     */
    private function checkAuthorized(): void
    {
        $user = auth()->user();
        if (!$user || ! in_array($user->role, ['admin','directeur'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Affiche la liste des UEs avec filtres GET.
     */
    public function index(Request $request)
    {
        $this->checkAuthorized();

        $query = UniteEnseignement::query();

        // Appliquer les filtres passés en query string
        if ($request->filled('intitule')) {
            $query->where('intitule', 'like', '%'.$request->intitule.'%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('niveau_scolaire')) {
            $query->where('niveau_scolaire', $request->niveau_scolaire);
        }
        if ($request->filled('annee_universitaire')) {
            $query->where('annee_universitaire', $request->annee_universitaire);
        }

        // Pagination et conservation des query strings
        $unites = $query->paginate(15)->withQueryString();

        // Valeurs distinctes pour peupler les selects de filtres
        $types   = UniteEnseignement::distinct()->pluck('type');
        $niveaux = UniteEnseignement::distinct()->pluck('niveau_scolaire');
        $annees  = UniteEnseignement::distinct()->pluck('annee_universitaire');

        return view('unites_enseignement.index', [
            'unites'            => $unites,
            'types'             => $types,
            'niveaux'           => $niveaux,
            'annees'            => $annees,
            'filter_intitule'   => $request->intitule,
            'filter_type'       => $request->type,
            'filter_niveau'     => $request->niveau_scolaire,
            'filter_annee'      => $request->annee_universitaire,
        ]);
    }

    public function create()
    {
        $this->checkAuthorized();
        return view('unites_enseignement.create');
    }

    public function store(Request $request)
    {
        $this->checkAuthorized();

        $request->validate([
            'intitule'           => 'required|string|max:255',
            'type'               => 'required|string|max:255',
            'niveau_scolaire'    => 'required|string|max:255',
            'code'               => 'required|string|max:50|unique:unite_enseignements,code',
            'description'        => 'nullable|string',
            'date_creation'      => 'nullable|date',
            'date_fin'           => 'nullable|date',
            'annee_universitaire'=> 'required|string|max:255',
        ]);

        UniteEnseignement::create($request->all());

        return redirect()
            ->route('unites-enseignement.index')
            ->with('success', 'Unité d’enseignement ajoutée avec succès.');
    }

    public function show(int $id)
    {
        $this->checkAuthorized();
        $ue = UniteEnseignement::findOrFail($id);
        return view('unites_enseignement.show', compact('ue'));
    }

    public function edit(int $id)
    {
        $this->checkAuthorized();
        $ue = UniteEnseignement::findOrFail($id);
        return view('unites_enseignement.edit', compact('ue'));
    }

    public function update(Request $request, int $id)
    {
        $this->checkAuthorized();

        $ue = UniteEnseignement::findOrFail($id);

        $request->validate([
            'intitule'           => 'required|string|max:255',
            'type'               => 'required|string|max:255',
            'niveau_scolaire'    => 'required|string|max:255',
            'code'               => "required|string|max:50|unique:unite_enseignements,code,{$id},id_ue",
            'description'        => 'nullable|string',
            'date_creation'      => 'nullable|date',
            'date_fin'           => 'nullable|date',
            'annee_universitaire'=> 'required|string|max:255',
        ]);

        $ue->update($request->all());

        return redirect()
            ->route('unites-enseignement.index')
            ->with('success', 'Unité d’enseignement mise à jour avec succès.');
    }

    public function destroy(int $id)
    {
        $this->checkAuthorized();
        UniteEnseignement::findOrFail($id)->delete();
        return redirect()
            ->route('unites-enseignement.index')
            ->with('success', 'Unité d’enseignement supprimée avec succès.');
    }
}
