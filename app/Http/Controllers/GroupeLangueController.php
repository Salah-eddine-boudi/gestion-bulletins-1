<?php

namespace App\Http\Controllers;

use App\Models\GroupeLangue;
use Illuminate\Http\Request;

class GroupeLangueController extends Controller
{
    // Afficher tous les groupes de langue
    public function index()
    {
        $groupesLangue = GroupeLangue::all();
        return view('groupes_langue.index', compact('groupesLangue'));
    }

    // Formulaire d'ajout d'un groupe de langue
    public function create()
    {
        return view('groupes_langue.create');
    }

    // Sauvegarder un nouveau groupe de langue
    public function store(Request $request)
    {
        $validated = $request->validate([
            'langue' => 'required|string',
            'niveau' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'semestre' => 'required|string',
        ]);

        GroupeLangue::create($validated);

        return redirect()->route('groupes_langue.index')->with('success', 'Groupe de langue ajouté avec succès.');
    }

    // Afficher un groupe de langue spécifique
    public function show($id)
    {
        $groupeLangue = GroupeLangue::findOrFail($id);
        return view('groupes_langue.show', compact('groupeLangue'));
    }

    // Formulaire pour éditer un groupe de langue existant
    public function edit($id)
    {
        $groupeLangue = GroupeLangue::findOrFail($id);
        return view('groupes_langue.edit', compact('groupeLangue'));
    }

    // Mettre à jour un groupe de langue existant
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'langue' => 'required|string',
            'niveau' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'semestre' => 'required|string',
        ]);

        $groupeLangue = GroupeLangue::findOrFail($id);
        $groupeLangue->update($validated);

        return redirect()->route('groupes_langue.index')->with('success', 'Groupe de langue mis à jour avec succès.');
    }

    // Supprimer un groupe de langue existant
    public function destroy($id)
    {
        $groupeLangue = GroupeLangue::findOrFail($id);
        $groupeLangue->delete();

        return redirect()->route('groupes_langue.index')->with('success', 'Groupe de langue supprimé avec succès.');
    }
}
