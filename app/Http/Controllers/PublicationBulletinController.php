<?php

namespace App\Http\Controllers;

use App\Models\PublicationBulletin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PublicationBulletinController extends Controller
{
    /**
     * Affiche le bulletin (uniquement si authorized_by_dp = true).
     */
    public function show(PublicationBulletin $bulletin)
    {
        // authorize() vient du trait AuthorizesRequests
        $this->authorize('view', $bulletin);

        return view('bulletins.show', compact('bulletin'));
    }

    /**
     * Valide un bulletin et notifie l'étudiant.
     */
    public function authorizeByDp(PublicationBulletin $bulletin): RedirectResponse
    {
        // Vérifie la Policy authorizeByDp()
        $this->authorize('authorizeByDp', $bulletin);

        // Met à jour la colonne d'autorisation
        $bulletin->update([
            'is_authorized_by_dp' => true,
            'authorized_by'       => Auth::id(),  // <-- ici on récupère l'ID correctement
            'authorized_at'       => now(),
        ]);

        return back()->with('success', 'Bulletin autorisé et étudiant notifié.');
    }
}
