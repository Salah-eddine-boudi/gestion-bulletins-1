<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnseignerUe extends Model
{
    protected $table = 'enseigner_ue';
    public $timestamps = false;

    protected $fillable = [
        'id_ue',
        'annee_universitaire',
    ];

    public function uniteEnseignement(){
        return $this->belongsTo(UniteEnseignement::class, 'id_ue');
    }
}
