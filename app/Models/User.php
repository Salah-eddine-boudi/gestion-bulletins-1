<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs pouvant être assignés en masse.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'tel_pro',
        'statut',
        'photo',
    ];
    
    /**
     * Les attributs à cacher lors de la sérialisation.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 11 permet ce cast pour hasher automatiquement le mot de passe
    ];

    /**
     * Vérifier si l'utilisateur est un étudiant.
     *
     * @return bool
     */
    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    /**
     * Vérifier si l'utilisateur est un professeur.
     *
     * @return bool
     */
    public function isProfesseur(): bool
    {
        return $this->role === 'professeur';
    }

    /**
     * Vérifier si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est un directeur pédagogique.
     *
     * @return bool
     */
    public function isDirecteur(): bool
    {
        return $this->role === 'directeur';
    }

    /**
     * Relation avec le professeur (si l'utilisateur est un professeur).
     */
    public function professeur()
    {
        return $this->hasOne(Professeur::class, 'id_user'); // Un professeur appartient à un utilisateur
    }

    /**
     * Relation avec l'étudiant (si l'utilisateur est un étudiant).
     */
    public function eleve()
    {
        return $this->hasOne(Eleve::class, 'id_user'); // Un élève appartient à un utilisateur
    }

    /**
     * Relation avec l'administrateur (si l'utilisateur est un admin).
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user'); // Un admin appartient à un utilisateur
    }

    /**
     * Relation avec le directeur pédagogique (si l'utilisateur est un directeur).
     */
    public function directeur()
    {
        return $this->hasOne(DirecteurPedagogique::class, 'id_user'); // Un directeur appartient à un utilisateur
    }
}
