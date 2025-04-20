<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    // Spécifier le nom de la table si elle est personnalisée
    protected $table = 'professeurs';  // Nom de la table personnalisée

    // Définir la clé primaire
    protected $primaryKey = 'id_prof';  // La clé primaire est 'id_prof', pas 'id'

    // Indiquer que cette table ne possède pas de timestamps (created_at, updated_at)
    public $timestamps = false; // Si vous ne souhaitez pas utiliser les colonnes 'created_at' et 'updated_at'

    // Les colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'id_user',
        'adresse',
        'matricule',
        'grade',
        'regime_emploi',
        'specialite',
        'date_prise_fonction',
        'date_fin_mandat',
        'affiliation',
    ];

    /**
     * Définir la relation avec l'utilisateur.
     * Un professeur appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');  // Relation avec l'utilisateur
    }
    public function assurances()
{
    return $this->hasMany(Assurer::class, 'id_prof', 'id_prof');
}

}
