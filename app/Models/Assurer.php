<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assurer extends Model
{
    protected $table = 'assurer';
    protected $primaryKey = 'id_assurer';
    public $timestamps = false;

    protected $fillable = [
        'id_prof',
        'id_matiere',
        'date_debut',
        'date_fin',
        'appreciation',
        'vh_effectif',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere', 'id_matiere');
    }
    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_prof');
    }
}




   

