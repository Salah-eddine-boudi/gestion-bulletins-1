<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    // On pointe vers la table existante
    protected $table = 'groupe_langue';

    // Si la table n'a pas created_at / updated_at
    public $timestamps = false;

    // Clé primaire (si c'est 'id_groupe_langue', précisez-le)
    protected $primaryKey = 'id_groupe_langue';

    // Champs remplissables en masse
    protected $fillable = [
        'langue',
        'niveau',
        'date_debut',
        'date_fin',
        'semestre',
        'type_classe', // Différencie langue vs JM1/JM2/JM3...
    ];

    // Constantes pour faciliter la cohérence
    public const TYPE_LANGUE   = 'langue';
    public const TYPE_JM1      = 'JM1';
    public const TYPE_JM2      = 'JM2';
    public const TYPE_JM3_ISEN = 'JM3-ISEN';
    public const TYPE_JM3_HEI  = 'JM3-HEI';
    
    /**
     * Exemple de scope pour n'afficher que les classes de langue
     */
    public function scopeLangue($query)
    {
        return $query->where('type_classe', self::TYPE_LANGUE);
    }

    /**
     * Scope pour récupérer uniquement les classes d'un certain type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_classe', $type);
    }

    /**
     * Relation (exemple) pour récupérer les élèves
     * qui partagent le même "niveau" que la classe.
     * (Uniquement si la table `eleve` a une colonne "niveau".)
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'niveau', 'niveau');
    }
}
