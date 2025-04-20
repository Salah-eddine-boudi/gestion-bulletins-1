<?php

namespace App\Http\Controllers;

use App\Models\DirecteurPedagogique;
use App\Models\User;
use App\Models\Professeur;
use Illuminate\Http\Request;

class DirecteurPedagogiqueController extends Controller
{
    /**
     * Vérifie que l'utilisateur est un admin sinon renvoie 403.
     */
    private function checkAdminAccess()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }
    }

    public function index()
    {
        $this->checkAdminAccess();

        $directeurs = DirecteurPedagogique::with('user')->paginate(10);
        return view('directeurs.index', compact('directeurs'));
    }

    public function create()
    {
        $this->checkAdminAccess();

        $users = User::all();
        $professeurs = Professeur::all();
        return view('directeurs.create', compact('users', 'professeurs'));
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_prof' => 'nullable|exists:professeurs,id_prof',
            'date_prise_fonction' => 'required|date',
            'date_fin_mandat' => 'nullable|date|after:date_prise_fonction',
            'tel' => 'nullable|string',
            'bureau' => 'nullable|string',
            'appreciation' => 'nullable|string',
        ]);

        DirecteurPedagogique::create($validated);

        return redirect()->route('directeurs.index')->with('success', 'Directeur pédagogique ajouté.');
    }

    public function show($id)
    {
        $this->checkAdminAccess();

        $directeur = DirecteurPedagogique::with(['user', 'professeur'])->findOrFail($id);
        return view('directeurs.show', compact('directeur'));
    }

    public function edit($id)
    {
        $this->checkAdminAccess();

        $directeur = DirecteurPedagogique::with(['user', 'professeur'])->findOrFail($id);
        $users = User::all();
        $professeurs = Professeur::all();

        return view('directeurs.edit', compact('directeur', 'users', 'professeurs'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_prof' => 'nullable|exists:professeurs,id_prof',
            'date_prise_fonction' => 'required|date',
            'date_fin_mandat' => 'nullable|date|after:date_prise_fonction',
            'tel' => 'nullable|string',
            'bureau' => 'nullable|string',
            'appreciation' => 'nullable|string',
        ]);

        $directeur = DirecteurPedagogique::findOrFail($id);
        $directeur->update($validated);

        return redirect()->route('directeurs.index')->with('success', 'Directeur pédagogique mis à jour.');
    }

    public function destroy($id)
    {
        $this->checkAdminAccess();

        $directeur = DirecteurPedagogique::findOrFail($id);
        $directeur->delete();

        return redirect()->route('directeurs.index')->with('success', 'Directeur pédagogique supprimé.');
    }
}
