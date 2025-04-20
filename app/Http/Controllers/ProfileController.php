<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Mettre à jour les informations du profil, y compris la photo.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        try {
            // ✅ Validation des données
            $validatedData = $request->validate([
                'nom'     => ['required', 'string', 'max:255'],
                'prenom'  => ['required', 'string', 'max:255'],
                'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'tel_pro' => ['nullable', 'string', 'max:20'],
                'statut'  => ['required', 'in:actif,inactif'],
                'role'    => ['required', 'in:admin,professeur,eleve,directeur'],
                'photo'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // ✅ Trim des valeurs pour éviter les espaces involontaires
            $validatedData['nom'] = trim($validatedData['nom']);
            $validatedData['prenom'] = trim($validatedData['prenom']);
            $validatedData['email'] = trim($validatedData['email']);

            // ✅ Gestion de la photo de profil
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo sauf si c'est la photo par défaut
                if (!empty($user->photo) && $user->photo !== 'profile_pictures/default.png') {
                    $oldPhotoPath = 'public/' . $user->photo;
                    if (Storage::exists($oldPhotoPath)) {
                        Storage::delete($oldPhotoPath);
                    }
                }

                // Sauvegarde de la nouvelle image
                $photoPath = $request->file('photo')->store('profile_pictures', 'public');
                $validatedData['photo'] = $photoPath;
            }

            // ✅ Mise à jour des informations utilisateur
            $user->update($validatedData);

            // ✅ Réinitialiser la vérification d'email si modifié
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            return Redirect::route('profile.edit')->with('success', ' Profil mis à jour avec succès !');

        } catch (Exception $e) {
            return Redirect::route('profile.edit')->with('error', ' Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer le compte utilisateur et sa photo de profil.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);

            $user = Auth::user();

            // ✅ Déconnexion avant suppression
            Auth::logout();

            // ✅ Supprimer la photo sauf si c'est l'image par défaut
            if (!empty($user->photo) && $user->photo !== 'profile_pictures/default.png') {
                $oldPhotoPath = 'public/' . $user->photo;
                if (Storage::exists($oldPhotoPath)) {
                    Storage::delete($oldPhotoPath);
                }
            }

            // ✅ Suppression de l'utilisateur
            $user->delete();

            // ✅ Invalidation de la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('success', '🗑️ Votre compte a été supprimé avec succès.');

        } catch (Exception $e) {
            return Redirect::route('profile.edit')->with('error', ' Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
