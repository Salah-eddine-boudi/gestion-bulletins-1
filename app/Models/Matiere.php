<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParametrageEvaluation;

class Matiere extends Model
{
    use HasFactory;

    public $incrementing = true; // La clé primaire est auto-incrémentée
    protected $keyType = 'int';   // La clé primaire est un entier

    protected $table = 'matieres'; // Nom de la table
    protected $primaryKey = 'id_matiere'; // Clé primaire

    public $timestamps = false; // Pas de colonnes created_at et updated_at

    protected $fillable = [
        'id_ue',
        'intitule',
        'volume_horaire',
        'syllabus',
        'ects',
        'filiere',
        'date_creation',
        'date_fin',
        'est_validante',
        'code',
        'description',
        'annee_universitaire',
        'semestre',
    ];

    /**
     * Relation avec la table `unite_enseignements`.
     * Une matière appartient à une Unité d'Enseignement (UE).
     */
    public function uniteEnseignement()
    {
        return $this->belongsTo(UniteEnseignement::class, 'id_ue', 'id_ue');
    }

    /**
     * Relation avec la table `evaluations`.
     * Une matière peut avoir plusieurs évaluations.
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_matiere', 'id_matiere');
    }
    public function parametrages()
{
    // On récupère tous les réglages faits par les profs pour cette matière
    return $this->hasMany(ParametrageEvaluation::class, 'id_matiere', 'id_matiere');
}
}
