<?php

namespace App\Http\Controllers;

use App\Models\UniteEnseignement;
use Illuminate\Http\Request;

class UniteEnseignementController extends Controller
{
    /**
     * Vérifie que l'utilisateur est autorisé (admin ou directeur).
     */
    private function checkAuthorized()
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'directeur'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function index()
    {
        $this->checkAuthorized();

        $unites = UniteEnseignement::all();
        return view('unites_enseignement.index', compact('unites'));
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
            'intitule' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'niveau_scolaire' => 'required|string|max:255',
            'code' => 'required|string|unique:unites_enseignement,code|max:50',
            'description' => 'nullable|string',
            'date_creation' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'annee_universitaire' => 'required|string|max:255',
        ]);

        UniteEnseignement::create($request->all());

        return redirect()->route('unites-enseignement.index')->with('success', 'Unité d\'enseignement ajoutée avec succès.');
    }

    public function show($id)
    {
        $this->checkAuthorized();

        $ue = UniteEnseignement::findOrFail($id);
        return view('unites_enseignement.show', compact('ue'));
    }

    public function edit($id)
    {
        $this->checkAuthorized();

        $ue = UniteEnseignement::findOrFail($id);
        return view('unites_enseignement.editu', compact('ue'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAuthorized();

        $ue = UniteEnseignement::findOrFail($id);

        $request->validate([
            'intitule' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'niveau_scolaire' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:unites_enseignement,code,' . $id . ',id_ue',
            'description' => 'nullable|string',
            'date_creation' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'annee_universitaire' => 'required|string|max:255',
        ]);

        $ue->update($request->all());

        return redirect()->route('unites-enseignement.index')->with('success', 'Unité d\'enseignement mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $this->checkAuthorized();

        $ue = UniteEnseignement::findOrFail($id);
        $ue->delete();

        return redirect()->route('unites-enseignement.index')->with('success', 'Unité d\'enseignement supprimée avec succès.');
    }
}
