<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Exception;

class ProfileController extends Controller
{
    /**
     * Afficher la page d'édition du profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mettre à jour les informations du profil, y compris photo et mot de passe.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1️⃣ Définition des règles de validation de base
        $rules = [
            'nom'     => ['required', 'string', 'max:255'],
            'prenom'  => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'tel_pro' => ['nullable', 'string', 'max:20'],
            'statut'  => ['required', 'in:actif,inactif'],
            'role'    => ['required', 'in:admin,professeur,eleve,directeur'],
            'photo'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];

        // 2️⃣ Si l’utilisateur veut changer son mot de passe
        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password']         = ['required', 'string', 'min:8', 'confirmed'];
        }

        // 3️⃣ Validation
        $validated = $request->validate($rules);

        try {
            // 4️⃣ Nettoyage des champs texte
            $validated['nom']    = trim($validated['nom']);
            $validated['prenom'] = trim($validated['prenom']);
            $validated['email']  = trim($validated['email']);

            // 5️⃣ Gestion de la photo de profil
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si non par défaut
                if (!empty($user->photo) && $user->photo !== 'profile_pictures/default.png') {
                    Storage::delete('public/' . $user->photo);
                }
                // Stocker la nouvelle
                $validated['photo'] = $request->file('photo')->store('profile_pictures', 'public');
            }

            // 6️⃣ Mise à jour des champs basiques
            $user->nom     = $validated['nom'];
            $user->prenom  = $validated['prenom'];
            $user->email   = $validated['email'];
            $user->tel_pro = $validated['tel_pro'] ?? $user->tel_pro;
            $user->statut  = $validated['statut'];
            $user->role    = $validated['role'];

            if (isset($validated['photo'])) {
                $user->photo = $validated['photo'];
            }

            // 7️⃣ Réinitialiser la vérif. email si l’adresse a changé
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // 8️⃣ Changement du mot de passe si demandé
            if (isset($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            // 9️⃣ Sauvegarde finale
            $user->save();

            return Redirect::route('profile.edit')
                ->with('success', 'Profil mis à jour avec succès !');

        } catch (Exception $e) {
            return Redirect::route('profile.edit')
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer le compte utilisateur et sa photo de profil.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validation du mot de passe
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // Déconnexion
            Auth::logout();

            // Supprimer la photo si non par défaut
            if (!empty($user->photo) && $user->photo !== 'profile_pictures/default.png') {
                Storage::delete('public/' . $user->photo);
            }

            // Suppression
            $user->delete();

            // Invalidation de la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')
                ->with('success', 'Votre compte a été supprimé avec succès.');

        } catch (Exception $e) {
            return Redirect::route('profile.edit')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
