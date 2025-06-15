<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    // If you have fillable columns:
    protected $fillable = [
        'langue',
        'niveau',
        'date_debut',
        'date_fin',
        'semestre',
    ];
}

