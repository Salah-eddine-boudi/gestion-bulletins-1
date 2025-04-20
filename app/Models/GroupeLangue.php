<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeLangue extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'groupe_langue';

    // Clé primaire
    protected $primaryKey = 'id_groupe_langue';

    // Colonnes qui peuvent être assignées en masse
    protected $fillable = [
        'langue',
        'niveau',
        'date_debut',
        'date_fin',
        'semestre',
    ];

    // Définir les relations avec d'autres modèles si nécessaire
    // Par exemple, un groupe de langue a plusieurs élèves
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'id_groupe_langue');
    }
}
