<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Http\Request;

class ProfesseurController extends Controller
{
    /**
     * Vérifie que l'utilisateur est un admin ou un directeur pédagogique.
     */
    private function checkAccess()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'directeur'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function index()
    {
        $this->checkAccess();
        $professeurs = Professeur::with('user')->get();
        return view('professeurs.index', compact('professeurs'));
    }

    public function create()
    {
        $this->checkAccess();
        // Récupérer TOUS les utilisateurs et non uniquement ceux ayant le rôle "professeur"
        $users = User::all();
        // Récupérer les matières dont l'unité d'enseignement a le niveau scolaire "JM1"
        $matieres = Matiere::whereHas('uniteEnseignement', function ($q) {
            $q->where('niveau_scolaire', 'JM1');
        })->get();
        return view('professeurs.create', compact('users', 'matieres'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'id_user'             => 'required|exists:users,id',
            'adresse'             => 'required|string',
            'matricule'           => 'required|string',
            'grade'               => 'required|string',
            'regime_emploi'       => 'required|string',
            'specialite'          => 'nullable|string',
            'date_prise_fonction' => 'required|date',
            'date_fin_mandat'     => 'nullable|date',
            'affiliation'         => 'nullable|string',
            'matieres'            => 'required|array',
            'matieres.*'          => 'exists:matieres,id_matiere',
            'date_debut'          => 'required|date',
            'date_fin'            => 'required|date|after_or_equal:date_debut',
            'vh_effectif'         => 'required|integer|min:0',
        ]);

        // Création du professeur
        $professeur = Professeur::create([
            'id_user'             => $validated['id_user'],
            'adresse'             => $validated['adresse'],
            'matricule'           => $validated['matricule'],
            'grade'               => $validated['grade'],
            'regime_emploi'       => $validated['regime_emploi'],
            'specialite'          => $validated['specialite'] ?? null,
            'date_prise_fonction' => $validated['date_prise_fonction'],
            'date_fin_mandat'     => $validated['date_fin_mandat'] ?? null,
            'affiliation'         => $validated['affiliation'] ?? null,
        ]);

        // Insertion dans la table "assurer" pour l'affectation des matières
        foreach ($validated['matieres'] as $id_matiere) {
            \DB::table('assurer')->insert([
                'id_prof'     => $professeur->id_prof,
                'id_matiere'  => $id_matiere,
                'date_debut'  => $validated['date_debut'],
                'date_fin'    => $validated['date_fin'],
                'vh_effectif' => $validated['vh_effectif'],
                'appreciation' => null,
            ]);
        }

        return redirect()->route('professeurs.index')
                         ->with('success', 'Professeur ajouté avec succès.');
    }

    public function show($id)
    {
        $this->checkAccess();
        $professeur = Professeur::with('user')->findOrFail($id);
        return view('professeurs.show', compact('professeur'));
    }

    public function edit($id)
    {
        $this->checkAccess();
        $professeur = Professeur::with('user')->findOrFail($id);
        // Récupérer TOUS les utilisateurs
        $users = User::all();
        // Récupérer les matières dont l'unité d'enseignement a le niveau scolaire "JM1"
        $matieres = Matiere::whereHas('uniteEnseignement', function ($q) {
            $q->where('niveau_scolaire', 'JM1');
        })->get();
        return view('professeurs.edit', compact('professeur', 'users', 'matieres'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'id_user'             => 'required|exists:users,id',
            'adresse'             => 'required|string',
            'matricule'           => 'required|string',
            'grade'               => 'required|string',
            'regime_emploi'       => 'required|string',
            'specialite'          => 'nullable|string',
            'date_prise_fonction' => 'required|date',
            'date_fin_mandat'     => 'nullable|date',
            'affiliation'         => 'nullable|string',
            'matieres'            => 'required|array',
            'matieres.*'          => 'exists:matieres,id_matiere',
        ]);

        $professeur = Professeur::findOrFail($id);
        $professeur->update($validated);

        // Mise à jour des affectations dans la table "assurer"
        \DB::table('assurer')->where('id_prof', $professeur->id_prof)->delete();
        foreach ($validated['matieres'] as $id_matiere) {
            \DB::table('assurer')->insert([
                'id_prof'    => $professeur->id_prof,
                'id_matiere' => $id_matiere,
            ]);
        }

        return redirect()->route('professeurs.index')
                         ->with('success', 'Professeur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $this->checkAccess();
        $professeur = Professeur::findOrFail($id);
        \DB::table('assurer')->where('id_prof', $professeur->id_prof)->delete();
        $professeur->delete();

        return redirect()->route('professeurs.index')
                         ->with('success', 'Professeur supprimé avec succès.');
    }
}
