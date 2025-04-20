<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Vérifie si l'utilisateur est un administrateur.
     * Affiche une erreur 403 sinon.
     */
    private function checkAccess()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function index()
    {
        $this->checkAccess();

        $admins = Admin::with('user')->get();
        $usersAdmins = User::where('role', 'admin')->get();

        return view('admins.index', compact('admins', 'usersAdmins'));
    }

    public function create()
    {
        $this->checkAccess();

        $users = User::all();
        return view('admins.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'role' => 'required|string',
            'acces' => 'required|string',
            'tel' => 'nullable|string',
            'bureau' => 'required|string',
        ]);

        Admin::create([
            'id_user' => $validated['id_user'],
            'role' => $validated['role'],
            'acces' => $validated['acces'],
            'tel' => $validated['tel'],
            'bureau' => $validated['bureau'],
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin ajouté avec succès.');
    }

    public function show($id)
    {
        $this->checkAccess();

        $admin = Admin::with('user')->findOrFail($id);
        return view('admins.show', compact('admin'));
    }

    public function edit($id)
    {
        $this->checkAccess();

        $admin = Admin::where('id_admin', $id)->with('user')->firstOrFail();
        $users = User::all();
        return view('admins.edit', compact('admin', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'role' => 'required|string',
            'acces' => 'required|string',
            'tel' => 'nullable|string',
            'bureau' => 'nullable|string',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update([
            'id_user' => $validated['id_user'],
            'role' => $validated['role'],
            'acces' => $validated['acces'],
            'tel' => $validated['tel'],
            'bureau' => $validated['bureau'],
        ]);

        return redirect()->route('admins.index')->with('success', 'Administrateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $this->checkAccess();

        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Administrateur supprimé.');
    }
}
