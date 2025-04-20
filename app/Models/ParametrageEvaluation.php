<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Professeur;
use App\Models\Matiere;

class ParametrageEvaluation extends Model
{
    use HasFactory;

    /**
     * Nom de la table si différent de la convention (plural du nom de la classe).
     */
    protected $table = 'parametrage_evaluations';

    /**
     * Clé primaire
     */
    protected $primaryKey = 'id_config';

    /**
     * Type de la clé primaire (ici un entier).
     */
    protected $keyType = 'int';

    /**
     * Indique si la clé primaire est auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Active les timestamps (created_at / updated_at).
     */
    public $timestamps = true;

    /**
     * Attributs assignables en masse.
     */
    protected $fillable = [
        'id_professeur',
        'id_matiere',
        'type',
        'nombre_evaluations',
        'pourcentage',
    ];

    /**
     * Casts pour forcer le type de certaines colonnes.
     */
    protected $casts = [
        'id_professeur'      => 'integer',
        'id_matiere'         => 'integer',
        'nombre_evaluations' => 'integer',
        'pourcentage'        => 'float',
    ];

    /**
     * Relation vers le professeur qui a défini ce paramétrage.
     */
    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_professeur', 'id_prof');
    }

    /**
     * Relation vers la matière concernée.
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere', 'id_matiere');
    }
}
