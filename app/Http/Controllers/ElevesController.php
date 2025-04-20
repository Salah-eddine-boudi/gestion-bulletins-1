<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\User;
use Illuminate\Http\Request;

class ElevesController extends Controller
{
    /**
     * Vérifie si l'utilisateur est autorisé (professeur, admin ou directeur).
     */
    private function checkAuthorizedAccess()
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['professeur', 'admin', 'directeur'])) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function index()
    {
        $this->checkAuthorizedAccess();

        $eleves = Eleve::with('user')->paginate(10);
        return view('eleves.index', compact('eleves'));
    }

    public function create()
    {
        $this->checkAuthorizedAccess();

        $users = User::where('role', 'eleve')->get();
        return view('eleves.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->checkAuthorizedAccess();

        $request->validate([
            'id_user' => 'required|exists:users,id',
            'date_naissance' => 'required|date',
            'matricule' => 'required|string|unique:eleves,matricule',
            'niveau_scolaire' => 'required|string',
            'specialite' => 'nullable|string',
            'date_inscription' => 'required|date',
            'photo_identite' => 'nullable|string',
            'status' => 'required|in:actif,archivé',
        ]);

        $eleve = Eleve::create([
            'id_user' => $request->id_user,
            'date_naissance' => $request->date_naissance,
            'matricule' => $request->matricule,
            'niveau_scolaire' => $request->niveau_scolaire,
            'specialite' => $request->specialite,
            'date_inscription' => $request->date_inscription,
            'photo_identite' => $request->photo_identite ?? 'default.jpg',
            'status' => $request->status,
        ]);

        // Forcer le rôle à "eleve" si ce n'est pas le cas
        $user = User::find($request->id_user);
        if ($user && $user->role !== 'eleve') {
            $user->update(['role' => 'eleve']);
        }

        return redirect()->route('eleves.index')->with('success', 'Élève ajouté avec succès.');
    }

    public function show(Eleve $eleve)
    {
        $this->checkAuthorizedAccess();

        return view('eleves.show', compact('eleve'));
    }

    public function edit($id_eleve)
    {
        $this->checkAuthorizedAccess();

        $eleve = Eleve::with('user')->findOrFail($id_eleve);
        return view('eleves.edit', compact('eleve'));
    }

    public function update(Request $request, $id_eleve)
    {
        $this->checkAuthorizedAccess();

        $eleve = Eleve::findOrFail($id_eleve);

        $request->validate([
            'date_naissance' => 'required|date',
            'matricule' => 'required|string|unique:eleves,matricule,' . $eleve->id_eleve . ',id_eleve',
            'niveau_scolaire' => 'required|string',
            'specialite' => 'nullable|string',
            'date_inscription' => 'required|date',
            'photo_identite' => 'nullable|string',
            'status' => 'required|in:actif,archivé',
        ]);

        $eleve->update($request->all());

        return redirect()->route('eleves.index')->with('success', 'Élève mis à jour avec succès.');
    }

    public function destroy($id_eleve)
    {
        $this->checkAuthorizedAccess();

        $eleve = Eleve::findOrFail($id_eleve);
        $eleve->delete();

        return redirect()->route('eleves.index')->with('success', 'Élève supprimé avec succès.');
    }
}
