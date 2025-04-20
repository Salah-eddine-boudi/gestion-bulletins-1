<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Professeur;

class ProfesseurSeeder extends Seeder
{
    public function run()
    {
        // Liste des utilisateurs avec leurs données
        $users = [
            ['nom' => 'Boudi', 'prenom' => 'Salah Eddine', 'email' => 'salah.eddine@example.com', 'role' => 'professeur'],
            ['nom' => 'El Amrani', 'prenom' => 'Ahmed', 'email' => 'ahmed.elamrani@example.com', 'role' => 'professeur'],
            ['nom' => 'Zeroual', 'prenom' => 'Fatima', 'email' => 'fatima.zeroual@example.com', 'role' => 'professeur'],
            ['nom' => 'Hassan', 'prenom' => 'Yassine', 'email' => 'yassine.hassan@example.com', 'role' => 'professeur'],
            ['nom' => 'Jabari', 'prenom' => 'Khalid', 'email' => 'khalid.jabari@example.com', 'role' => 'professeur'],
            ['nom' => 'Benchekroun', 'prenom' => 'Meriem', 'email' => 'meriem.benchekroun@example.com', 'role' => 'professeur'],
            ['nom' => 'Ait Ali', 'prenom' => 'Omar', 'email' => 'omar.aitali@example.com', 'role' => 'professeur'],
            ['nom' => 'Charki', 'prenom' => 'Karim', 'email' => 'karim.charki@example.com', 'role' => 'professeur'],
            ['nom' => 'Saidi', 'prenom' => 'Hassan', 'email' => 'hassan.saidi@example.com', 'role' => 'professeur'],
            ['nom' => 'Rachid', 'prenom' => 'Zohra', 'email' => 'zohra.rachid@example.com', 'role' => 'professeur'],
            // Ajout de 5 autres professeurs
            ['nom' => 'Tazi', 'prenom' => 'Ahmed', 'email' => 'ahmed.tazi@example.com', 'role' => 'professeur'],
            ['nom' => 'Kouadio', 'prenom' => 'Michel', 'email' => 'michel.kouadio@example.com', 'role' => 'professeur'],
            ['nom' => 'Lahmar', 'prenom' => 'Youssef', 'email' => 'youssef.lahmar@example.com', 'role' => 'professeur'],
            ['nom' => 'Ziani', 'prenom' => 'Mohamed', 'email' => 'mohamed.ziani@example.com', 'role' => 'professeur'],
            ['nom' => 'Mansouri', 'prenom' => 'Karim', 'email' => 'karim.mansouri@example.com', 'role' => 'professeur'],
        ];

        foreach ($users as $user) {
            // Créer un utilisateur avec un mot de passe haché (salah2004)
            $createdUser = User::create([
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'password' => bcrypt('salah2004'), // Utilisation de bcrypt pour hacher le mot de passe
                'role' => $user['role'],
                'tel_pro' => '0612345678',
                'statut' => 'actif',
                'photo' => 'default.jpg',
            ]);

            // Créer le professeur associé à cet utilisateur
            Professeur::create([
                'id_user' => $createdUser->id, // Associer l'utilisateur au professeur
                'adresse' => 'Adresse de test',
                'matricule' => 'matricule123',
                'grade' => 'Grade de test',
                'regime_emploi' => 'plein temps',
                'specialite' => 'Informatique',
                'date_prise_fonction' => now(),
                'affiliation' => 'Affiliation exemple',
            ]);
        }
    }
}
