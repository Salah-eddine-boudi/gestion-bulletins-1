<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Professeur;
use App\Models\Assurer;

class ProfesseurRollbackSeeder extends Seeder
{
    public function run()
    {
        // Supprimer d'abord les enregistrements dans la table 'assurer'
        Assurer::truncate(); // Supprime les enregistrements qui ont des clés étrangères sur 'professeurs'
        
        // Supprimer les professeurs associés aux utilisateurs
        Professeur::truncate(); // Supprime tous les enregistrements de la table professeurs
        
        // Supprimer les utilisateurs avec le rôle 'professeur'
        User::where('role', 'professeur')->delete(); // Supprime les utilisateurs de type professeur
    }
}
