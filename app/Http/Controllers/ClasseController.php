<?php

namespace App\Http\Controllers;

use App\Models\Classe; // notre modèle
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    // 1. Lister tous les enregistrements (équivalent d'un "index")
    

    public function index()
    {
        // Retrieve a collection (e.g. Eloquent collection) of Classe
        $classes = Classe::all(); // or ->paginate(), etc.
        
        // Pass this collection to the view
        return view('classes.index', ['classes' => Classe::all()]);

    }
    

    


    // 2. Afficher le formulaire de création
    public function create()
    {
        return view('classes.create');
    }

    // 3. Enregistrer la nouvelle "classe" (groupe_langue) en BDD
    public function store(Request $request)
    {
        // Valider les champs reçus
        $request->validate([
            'langue' => 'required|string',
            'niveau' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'semestre' => 'nullable|string',
        ]);

        // Créer l’enregistrement
        Classe::create($request->all());
        return redirect()->route('classes.index')
                         ->with('success', 'Classe (groupe) créée avec succès !');
    }

    // 4. Afficher le détail d’un enregistrement
    public function show($id)
    {
        $classe = Classe::findOrFail($id);
        return view('classes.show', compact('classe'));
    }

    // 5. Afficher le formulaire d’édition
    public function edit($id)
    {
        $classe = Classe::findOrFail($id);
        return view('classes.edit', compact('classe'));
    }

    // 6. Mettre à jour un enregistrement
    public function update(Request $request, $id)
    {
        $request->validate([
            'langue' => 'required|string',
            'niveau' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'semestre' => 'nullable|string',
        ]);

        $classe = Classe::findOrFail($id);
        $classe->update($request->all());
        return redirect()->route('classes.index')
                         ->with('success', 'Classe mise à jour avec succès !');
    }

    // 7. Supprimer un enregistrement
    public function destroy($id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();
        return redirect()->route('classes.index')
                         ->with('success', 'Classe supprimée avec succès !');
    }
}
