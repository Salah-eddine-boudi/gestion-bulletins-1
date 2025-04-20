<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'eleves';
    
    protected $primaryKey = 'id_eleve'; // <-- PK personnalisée

    // Clé primaire
    

    // Les attributs qui peuvent être remplis en masse
    protected $fillable = [
        'id_user',            // L'ID de l'utilisateur lié à l'élève
        'date_naissance',     // Date de naissance de l'élève
        'telephone',          // Téléphone de l'élève
        'adresse',            // Adresse de l'élève
        'niveau_scolaire',    // Niveau scolaire de l'élève (JM1, JM2, JM3)
        'specialite',         // Spécialité de l'élève (Tronc Commun, ISEN, HEI)
        'date_inscription',   // Date d'inscription de l'élève
        'photo_identite',     // Photo d'identité de l'élève
        'status',             // Statut de l'élève (actif, archivé)
    ];

    /**
     * Relation avec la table Users (Héritage)
     * Un élève appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');  // Assurez-vous que l'ID de l'élève est correctement lié à l'utilisateur
    }

    /**
     * Relation avec les évaluations
     * Un élève peut avoir plusieurs évaluations.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_eleve');  // L'élève a plusieurs évaluations
    }
    
    
}
