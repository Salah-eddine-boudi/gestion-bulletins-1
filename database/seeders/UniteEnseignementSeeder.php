<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UniteEnseignement;

class UniteEnseignementSeeder extends Seeder {
    public function run() {
        $ues = [
            [
                'intitule' => 'Mathématiques Appliquées',
                'type' => 'Fondamentale',
                'niveau_scolaire' => 'JM1',
                'code' => 'MATH101',
                'description' => 'Cours avancé en mathématiques appliquées.',
                'date_creation' => '2023-09-01',
                'date_fin' => '2024-06-30',
                'annee_universitaire' => '2023-2024'
            ],
            [
                'intitule' => 'Informatique Générale',
                'type' => 'Fondamentale',
                'niveau_scolaire' => 'JM1',
                'code' => 'INFO102',
                'description' => 'Introduction aux concepts fondamentaux en informatique.',
                'date_creation' => '2023-09-01',
                'date_fin' => '2024-06-30',
                'annee_universitaire' => '2023-2024'
            ],
            [
                'intitule' => 'Bases de Données',
                'type' => 'Fondamentale',
                'niveau_scolaire' => 'JM2',
                'code' => 'BD303',
                'description' => 'Introduction aux bases de données relationnelles.',
                'date_creation' => '2023-09-01',
                'date_fin' => '2024-06-30',
                'annee_universitaire' => '2023-2024'
            ],
            [
                'intitule' => 'Programmation Web',
                'type' => 'Optionnelle',
                'niveau_scolaire' => 'JM2',
                'code' => 'WEB202',
                'description' => 'Cours sur les bases du développement web.',
                'date_creation' => '2023-09-01',
                'date_fin' => '2024-06-30',
                'annee_universitaire' => '2023-2024'
            ],
            [
                'intitule' => 'Sécurité Informatique',
                'type' => 'Fondamentale',
                'niveau_scolaire' => 'JM3-ISEN',
                'code' => 'SEC401',
                'description' => 'Principes de cybersécurité et de protection des systèmes.',
                'date_creation' => '2023-09-01',
                'date_fin' => '2024-06-30',
                'annee_universitaire' => '2023-2024'
            ]
        ];

        foreach ($ues as $ue) {
            UniteEnseignement::create($ue);
        }
    }
}
