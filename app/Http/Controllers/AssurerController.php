<?php

namespace App\Http\Controllers;

use App\Models\Assurer;
use App\Models\Professeur;
use App\Models\Matiere;
use Illuminate\Http\Request;

class AssurerController extends Controller
{
    // Affiche la liste des enseignements assurés
    public function index()
    {
        $assurances = Assurer::with(['professeur', 'matiere'])->get();
        return view('assurer.index', compact('assurances'));
    }

    // Affiche le formulaire pour créer un nouvel enseignement assuré
    public function create()
    {
        $professeurs = Professeur::all();
        $matieres = Matiere::all();

        return view('assurer.create', compact('professeurs', 'matieres'));
    }

    // Sauvegarde un nouvel enseignement assuré
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_prof'      => 'required|exists:professeurs,id_prof',
            'id_matiere'   => 'required|exists:matieres,id_matiere',
            'date_debut'   => 'required|date',
            'date_fin'     => 'required|date|after:date_debut',
            'appreciation' => 'nullable|string',
            'vh_effectif'  => 'required|integer|min:0',
        ]);

        Assurer::create($validated);

        return redirect()->route('assurer.index')->with('success', 'Enseignement ajouté avec succès.');
    }

    // Affiche un enseignement spécifique assuré
    public function show(string $id)
    {
        $assurer = Assurer::with(['professeur', 'matiere'])->findOrFail($id);

        return view('assurer.show', compact('assurer'));
    }

    // Formulaire d'édition d'un enseignement assuré existant
    public function edit(string $id)
    {
        $assurer = Assurer::findOrFail($id);
        $professeurs = Professeur::all();
        $matieres = Matiere::all();

        return view('assurer.edit', compact('assurer', 'professeurs', 'matieres'));
    }

    // Met à jour l'enseignement assuré existant
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_prof'      => 'required|exists:professeurs,id_prof',
            'id_matiere'   => 'required|exists:matieres,id_matiere',
            'date_debut'   => 'required|date',
            'date_fin'     => 'required|date|after:date_debut',
            'appreciation' => 'nullable|string',
            'vh_effectif'  => 'required|integer|min:0',
        ]);

        $assurer = Assurer::findOrFail($id);
        $assurer->update($validated);

        return redirect()->route('assurer.index')->with('success', 'Enseignement mis à jour avec succès.');
    }

    // Supprime un enseignement assuré
    public function destroy(string $id)
    {
        $assurer = Assurer::findOrFail($id);
        $assurer->delete();

        return redirect()->route('assurer.index')->with('success', 'Enseignement supprimé avec succès.');
    }
}
