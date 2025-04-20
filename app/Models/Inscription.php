<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $table = 'inscriptions';
    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'annee_universitaire'
    ];

    public function eleve(){
        return $this->belongsTo(Eleve::class, 'id_eleve');
    }
}

