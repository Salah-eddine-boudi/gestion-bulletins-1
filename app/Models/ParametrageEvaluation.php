<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParametrageEvaluation extends Model
{
    use HasFactory;

    /** Table & clé primaire ----------------------------------------- */
    protected $table      = 'parametrage_evaluations';
    protected $primaryKey = 'id_config';
    public    $timestamps = true;                // created_at & updated_at

    /** Attributs autorisés en écriture (mass-assignment) ------------- */
    protected $fillable = [
        'id_professeur',
        'id_matiere',
        'type',                     // DS ou EXAM
        'nombre_evaluations',
        'pourcentage',
        'override_rattrapage',
        'pourcentage_rattrapage',
        'pourcentage_examen_final',
    ];

    /** Casts automatiques ------------------------------------------- */
    protected $casts = [
        'override_rattrapage'      => 'boolean',
        'pourcentage'              => 'decimal:2',
        'pourcentage_rattrapage'   => 'decimal:2',
        'pourcentage_examen_final' => 'decimal:2',
    ];

    /* ===============================================================
     |  Relations Eloquent
     |===============================================================*/

    // Professeur propriétaire
    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_professeur', 'id_prof');
    }

    // Matière concernée
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere', 'id_matiere');
    }
}
