<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniteEnseignement extends Model {
    use HasFactory;

    // Corrected the table name to match the actual database table name
    protected $table = 'unite_enseignements'; // Correct table name with "s"
    protected $primaryKey = 'id_ue';

    protected $fillable = [
        'intitule',
        'type',
        'niveau_scolaire',
        'code',
        'description',
        'date_creation',
        'date_fin',
        'annee_universitaire'
    ];

    public $timestamps = true;

    public function matieres()
    {
        return $this->hasMany(Matiere::class, 'id_ue');  // id_ue est la clé étrangère dans la table matieres
    }
    
}
