<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    // Définir le nom de la table si ce n'est pas le nom par défaut (pluriel du modèle)
    protected $table = 'evaluation'; // Vérifiez le nom exact de la table

    // Clé primaire si elle diffère de 'id'
    protected $primaryKey = 'id_evaluation'; // Vérifiez que la clé primaire est correcte

    // Désactiver les timestamps si la table ne les contient pas
    public $timestamps = false;

    // Définir les champs qui peuvent être remplis
    protected $fillable = [
        'id_eleve',
        'id_matiere',
        'type',
        'date_evaluation',
        'note',
        'presence',
    ];

    /**
     * Relation avec `Eleve`
     * Une évaluation appartient à un seul élève.
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'id_eleve', 'id_eleve');
    }

    /**
     * Relation avec `Matiere`
     * Une évaluation est liée à une seule matière.
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere', 'id_matiere');
    }

    /**
     * Relation avec `UniteEnseignement`
     * Une évaluation appartient à une unité d'enseignement.
     */
    public function uniteEnseignement()
    {
        return $this->belongsTo(UniteEnseignement::class, 'id_ue', 'id_ue');
    }
}
