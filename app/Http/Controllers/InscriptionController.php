<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    // Afficher toutes les inscriptions
    public function index()
    {
        $inscriptions = Inscription::with('eleve')->get();
        return view('inscriptions.index', compact('inscriptions'));
    }

    // Afficher le formulaire d'inscription
    public function create()
    {
        $eleves = Eleve::all();
        return view('inscriptions.create', compact('eleves'));
    }

    // Enregistrer une nouvelle inscription
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_eleve' => 'required|exists:eleves,id_eleve',
            'annee_universitaire' => 'required|string|max:10'
        ]);

        Inscription::create($validated);

        return redirect()->route('inscriptions.index')
            ->with('success', 'Inscription ajoutée avec succès.');
    }

    // Afficher une inscription spécifique
    public function show(string $id_eleve)
    {
        $inscription = Inscription::with('eleve')->findOrFail($id_eleve);
        return view('inscriptions.show', compact('inscription'));
    }

    // Formulaire de modification d'une inscription existante
    public function edit(string $id_eleve)
    {
        $inscription = Inscription::findOrFail($id_eleve);
        $eleves = Eleve::all();

        return view('inscriptions.edit', compact('inscription', 'eleves'));
    }

    // Mise à jour d'une inscription existante
    public function update(Request $request, string $id_eleve)
    {
        $validated = $request->validate([
            'id_eleve' => 'required|exists:eleves,id_eleve',
            'annee_universitaire' => 'required|string|max:10'
        ]);

        $inscription = Inscription::findOrFail($id_eleve);
        $inscription->update($validated);

        return redirect()->route('inscriptions.index')
            ->with('success', 'Inscription mise à jour avec succès.');
    }

    // Suppression d'une inscription
    public function destroy(string $id_eleve)
    {
        $inscription = Inscription::findOrFail($id_eleve);
        $inscription->delete();

        return redirect()->route('inscriptions.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }
}
