<?php

namespace App\Policies;

use App\Models\PublicationBulletin;
use App\Models\User;

class PublicationBulletinPolicy
{
    /**
     * Détermine si l'utilisateur peut voir un bulletin.
     */
    public function view(User $user, PublicationBulletin $bulletin): bool
    {
        // Les admins et les directeurs pédagogiques voient tous les bulletins
        if (in_array($user->role, ['admin', 'directeur'])) {
            return true;
        }

        // Un étudiant ne voit que SON bulletin une fois validé
        return $user->role === 'etudiant'
            && $bulletin->eleve->id_user === $user->id_user
            && $bulletin->is_authorized_by_dp;
    }

    /**
     * Détermine si le directeur pédagogique peut autoriser la publication.
     */
    public function authorizeByDp(User $user, PublicationBulletin $bulletin): bool
    {
        return $user->role === 'directeur';
    }
}
