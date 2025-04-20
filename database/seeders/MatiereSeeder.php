<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('matieres')->insert([
            [
                'id_ue' => 1, 
                'intitule' => 'Mathématiques Avancées',
                'volume_horaire' => 60,
                'syllabus' => 'Algèbre, analyse, probabilités',
                'ects' => 6,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'MAT101',
                'description' => 'Cours de mathématiques avancées pour ingénieurs',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 1, 
                'intitule' => 'Algorithmes et Structures de Données',
                'volume_horaire' => 50,
                'syllabus' => 'Listes, arbres, graphes, tris',
                'ects' => 5,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF102',
                'description' => 'Introduction aux algorithmes et structures de données',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 2, 
                'intitule' => 'Bases de Données',
                'volume_horaire' => 40,
                'syllabus' => 'SQL, modélisation, optimisation',
                'ects' => 4,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF103',
                'description' => 'Concepts fondamentaux des bases de données relationnelles',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 2, 
                'intitule' => 'Programmation Orientée Objet',
                'volume_horaire' => 45,
                'syllabus' => 'Classes, héritage, polymorphisme',
                'ects' => 5,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF104',
                'description' => 'Introduction à la programmation orientée objet',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 3, 
                'intitule' => 'Systèmes d’Exploitation',
                'volume_horaire' => 40,
                'syllabus' => 'Gestion mémoire, processus, fichiers',
                'ects' => 4,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF105',
                'description' => 'Principes des systèmes d’exploitation modernes',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 3, 
                'intitule' => 'Réseaux Informatiques',
                'volume_horaire' => 50,
                'syllabus' => 'Modèle OSI, TCP/IP, routage',
                'ects' => 5,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF106',
                'description' => 'Introduction aux réseaux informatiques',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S1'
            ],
            [
                'id_ue' => 4, 
                'intitule' => 'Développement Web',
                'volume_horaire' => 45,
                'syllabus' => 'HTML, CSS, JavaScript, PHP',
                'ects' => 5,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF107',
                'description' => 'Développement de sites web dynamiques',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S2'
            ],
            [
                'id_ue' => 4, 
                'intitule' => 'Sécurité Informatique',
                'volume_horaire' => 40,
                'syllabus' => 'Cryptographie, attaques, protection',
                'ects' => 4,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF108',
                'description' => 'Principes de la cybersécurité',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S2'
            ],
            [
                'id_ue' => 5, 
                'intitule' => 'Intelligence Artificielle',
                'volume_horaire' => 50,
                'syllabus' => 'Machine Learning, Réseaux de neurones',
                'ects' => 6,
                'filiere' => 'Génie Informatique',
                'date_creation' => now(),
                'date_fin' => null,
                'est_validante' => true,
                'code' => 'INF109',
                'description' => 'Introduction à l’intelligence artificielle',
                'annee_universitaire' => '2024-2025',
                'semestre' => 'S2'
            ],
        ]);
    }
}
