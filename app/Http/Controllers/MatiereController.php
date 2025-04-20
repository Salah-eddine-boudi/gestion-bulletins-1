<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\UniteEnseignement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatiereController extends Controller
{
    /**
     * Autorise uniquement les admins et directeurs.
     */
    private function checkAdminOrDirecteur()
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'directeur'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Liste des matières (interdite aux élèves).
     */
    public function index(Request $request)
    {
        $this->checkAdminOrDirecteur();

        $filiere = $request->input('filiere');
        $annee_universitaire = $request->input('annee_universitaire');
        $semestre = $request->input('semestre');

        $query = Matiere::query();

        if ($filiere) {
            $query->where('filiere', $filiere);
        }

        if ($annee_universitaire) {
            $query->where('annee_universitaire', $annee_universitaire);
        }

        if ($semestre) {
            $query->where('semestre', $semestre);
        }

        $matieres = $query->get();

        return view('matieres.index', compact('matieres', 'filiere', 'annee_universitaire', 'semestre'));
    }

    /**
     * Affiche une seule matière.
     */
    public function show(Matiere $matiere)
    {
        $user = auth()->user();

        // Élève peut accéder à la matière uniquement s’il l’étudie
        if ($user->role === 'eleve') {
            $eleve = $user->eleve;

            if (!$eleve || !$eleve->matieres->contains($matiere->id_matiere)) {
                abort(403, 'Vous n\'avez pas accès à cette matière.');
            }
        }

        // Sinon (admin ou directeur), accès autorisé
        return view('matieres.show', compact('matiere'));
    }

    public function create()
    {
        $this->checkAdminOrDirecteur();

        $unites = UniteEnseignement::all();
        return view('matieres.create', compact('unites'));
    }

    public function store(Request $request)
    {
        $this->checkAdminOrDirecteur();

        $request->validate([
            'id_ue' => 'required|exists:unite_enseignements,id_ue',
            'intitule' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:matieres,code',
            'annee_universitaire' => 'required|string',
            'semestre' => 'required|string',
        ]);

        Matiere::create($request->all());

        return redirect()->route('matieres.index')->with('success', 'Matière ajoutée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        $this->checkAdminOrDirecteur();

        $unites = UniteEnseignement::all();
        return view('matieres.edit', compact('matiere', 'unites'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $this->checkAdminOrDirecteur();

        $request->validate([
            'id_ue' => 'required|exists:unite_enseignements,id_ue',
            'intitule' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:matieres,code,' . $matiere->id_matiere,
            'annee_universitaire' => 'required|string',
            'semestre' => 'required|string',
        ]);

        $matiere->update($request->all());

        return redirect()->route('matieres.index')->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy(Matiere $matiere)
    {
        $this->checkAdminOrDirecteur();

        $matiere->delete();

        return redirect()->route('matieres.index')->with('success', 'Matière supprimée avec succès.');
    }
}
