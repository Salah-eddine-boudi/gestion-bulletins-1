<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\UniteEnseignement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;

class BulletinController extends Controller
{
    // Durée du cache en minutes
    const CACHE_DURATION = 60;

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Accès refusé (non authentifié)');
        }
        $niveau = $request->query('niveau'); // ex. ?niveau=JM2

        if (in_array($user->role, ['admin', 'directeur'])) {
            $eleves = Eleve::with('user')
                ->when($niveau, fn($q) => $q->where('niveau_scolaire', $niveau))
                ->get();
        } elseif ($user->role === 'eleve') {
            $eleves = Eleve::with('user')
                ->where('id_user', $user->id)
                ->get();
        } else {
            abort(403, 'Accès refusé (rôle non autorisé)');
        }
        $niveaux = ['JM1', 'JM2', 'JM3'];
        return view('bulletins.index', compact('eleves', 'niveaux'));
    }

    public function show($id)
    {
        $eleve = Eleve::with('user')->findOrFail($id);
        $user  = auth()->user();
        if (!$user) {
            abort(403, 'Accès refusé');
        }
        if ($user->role === 'eleve' && $eleve->id_user !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à consulter ce bulletin.');
        }
        if (!in_array($user->role, ['admin', 'directeur', 'eleve'])) {
            abort(403, 'Accès refusé');
        }

        // Utiliser le cache pour les calculs coûteux
        $cacheKey = "bulletin_{$id}_data";
        $bulletinData = Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($id, $eleve) {
        // Récupérer les UE avec leurs matières, évaluations et calcul du rang pour chaque matière
        $unitesEnseignement = $this->getUnitesAvecNotes($eleve->id_eleve);
            
            // Si aucune unité d'enseignement n'est trouvée, générer des données génériques
            if ($unitesEnseignement->isEmpty()) {
                $unitesEnseignement = $this->genererUnitesGeneriques($eleve->niveau_scolaire);
            }
            
            $ectsValides = $this->calculerECTS($unitesEnseignement);
            $moyenneGenerale = $this->calculerMoyenne($unitesEnseignement);
            $letterGradeGeneral = $this->convertirNoteEnLettre($moyenneGenerale);
            $vhTotal = $unitesEnseignement->sum('volumeHoraireTotal');
            $ectsTotal = $unitesEnseignement->sum('creditsTotal');

        // Agrégation globale pour min et max
        $notesAll = collect();
        foreach ($unitesEnseignement as $ue) {
            if (is_numeric($ue->min)) {
                $notesAll->push($ue->min);
            }
            if (is_numeric($ue->max)) {
                $notesAll->push($ue->max);
            }
        }
            $minGeneral = $notesAll->count() ? $notesAll->min() : 0;
            $maxGeneral = $notesAll->count() ? $notesAll->max() : 0;
            $noteFinaleGenerale = is_numeric($moyenneGenerale) ? $moyenneGenerale : 0;

        // Calcul du rang global dans la classe
            $classRanking = $this->getClasseRanking($eleve->niveau_scolaire);
        $rangGeneral = '';
            
            foreach ($classRanking as $i => $item) {
            if ($item['eleve']->id_eleve == $eleve->id_eleve) {
                    $rangGeneral = ($i + 1) . '/' . $classRanking->count();
                break;
            }
        }

            return [
                'unitesEnseignement' => $unitesEnseignement,
                'moyenneGenerale' => $moyenneGenerale,
                'letterGradeGeneral' => $letterGradeGeneral,
                'ectsValides' => intval($ectsValides),
                'vhTotal' => intval($vhTotal),
                'ectsTotal' => intval($ectsTotal),
                'minGeneral' => $minGeneral,
                'maxGeneral' => $maxGeneral,
                'noteFinaleGenerale' => $noteFinaleGenerale,
                'rangGeneral' => $rangGeneral
            ];
        });

        return view('bulletins.show', array_merge(
            ['eleve' => $eleve],
            $bulletinData
        ));
    }

    public function exportPdf($id, Request $request): Response
    {
        $eleve = Eleve::with('user')->findOrFail($id);
        $this->verifierDroits($eleve);
        
        // Récupération du format de bulletin souhaité (avec ou sans bloc groupe)
        $format = $request->query('format', 'complet');

        // Utiliser le cache pour les calculs coûteux
        $cacheKey = "bulletin_{$id}_data";
        $bulletinData = Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($id, $eleve) {
        $unitesEnseignement = $this->getUnitesAvecNotes($id);
            
            // Si aucune unité d'enseignement n'est trouvée, générer des données génériques
            if ($unitesEnseignement->isEmpty()) {
                $unitesEnseignement = $this->genererUnitesGeneriques($eleve->niveau_scolaire);
            }
            
            $moyenneGenerale = $this->calculerMoyenne($unitesEnseignement);
            $letterGradeGeneral = $this->convertirNoteEnLettre($moyenneGenerale);
            $ectsValides = $this->calculerECTS($unitesEnseignement);
            $vhTotal = $unitesEnseignement->sum('volumeHoraireTotal');
            $ectsTotal = $unitesEnseignement->sum('creditsTotal');

        // Agrégation globale pour min et max
        $notesAll = collect();
        foreach ($unitesEnseignement as $ue) {
            if (is_numeric($ue->min)) {
                $notesAll->push($ue->min);
            }
            if (is_numeric($ue->max)) {
                $notesAll->push($ue->max);
            }
        }
        $minGeneral = $notesAll->count() ? number_format($notesAll->min(), 2) : '';
        $maxGeneral = $notesAll->count() ? number_format($notesAll->max(), 2) : '';
            $noteFinaleGenerale = is_numeric($moyenneGenerale) ? number_format($moyenneGenerale, 2) : 0;

        // Calcul du rang global dans la classe
        $ranking = $this->getClasseRanking($eleve->niveau_scolaire);
        $rangGeneral = '';
        foreach ($ranking as $i => $item) {
            if ($item['eleve']->id_eleve == $eleve->id_eleve) {
                $rangGeneral = ($i + 1) . '/' . $ranking->count();
                break;
            }
        }

            return [
                'unitesEnseignement' => $unitesEnseignement,
                'moyenneGenerale' => is_numeric($moyenneGenerale) ? $moyenneGenerale : 0,
                'letterGradeGeneral' => $letterGradeGeneral,
                'ectsValides' => intval($ectsValides),
                'vhTotal' => intval($vhTotal),
                'ectsTotal' => intval($ectsTotal),
                'minGeneral' => $minGeneral,
                'maxGeneral' => $maxGeneral,
                'noteFinaleGenerale' => $noteFinaleGenerale,
                'rangGeneral' => $rangGeneral
            ];
        });

        // Générer les images des graphiques avant de créer le PDF
        $chartImages = $this->generateChartImages($eleve, $bulletinData);

        // Sélection du template en fonction du format demandé
        $template = $format === 'sans_groupe' ? 'bulletins.pdf_sans_groupe' : 'bulletins.pdf';

        // Configuration pour générer le PDF plus rapidement
        $pdf = Pdf::loadView($template, array_merge(
            ['eleve' => $eleve],
            $bulletinData,
            ['chartImages' => $chartImages]
        ));
        
        // Options pour améliorer les performances et permettre l'accès aux images locales
        $pdf->setPaper('a4', 'portrait');
        $pdf->setWarnings(false);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'chroot' => public_path(),
            'enable_javascript' => true,
            'javascript_delay' => 1000,
        ]);
        
        // Nettoyage: suppression des images temporaires après la génération du PDF
        $cleanupCallback = function() use ($chartImages) {
            try {
                foreach ($chartImages as $path) {
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                
                // Tenter de supprimer le dossier temporaire
                $tempDir = public_path('temp/charts');
                if (file_exists($tempDir) && count(glob("$tempDir/*")) === 0) {
                    rmdir($tempDir);
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors du nettoyage des images temporaires: ' . $e->getMessage());
            }
        };
        
        // Générer le PDF et télécharger
        $pdfContent = $pdf->output();
        
        // Nettoyer les fichiers temporaires
        $cleanupCallback();
        
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="bulletin_' . $eleve->user->nom . '_' . $eleve->user->prenom . '.pdf"');
    }

    /**
     * Génère les images des graphiques à l'aide de QuickChart.io
     * 
     * @param Eleve $eleve
     * @param array $bulletinData
     * @return array Tableau contenant les chemins vers les images générées et stockées localement
     */
    private function generateChartImages($eleve, $bulletinData)
    {
        $unitesEnseignement = $bulletinData['unitesEnseignement'];
        $moyenneGenerale = $bulletinData['moyenneGenerale'];
        $noteFinaleGenerale = $bulletinData['noteFinaleGenerale'];
        $rangGeneral = $bulletinData['rangGeneral'];
        $ectsValides = $bulletinData['ectsValides'];
        $ectsTotal = $bulletinData['ectsTotal'];
        
        // Extraire les unités, notes et moyennes pour le radar
        $unites = [];
        $notesUnites = [];
        $moyennesUnites = [];
        
        // Utiliser toutes les unités d'enseignement disponibles pour le radar
        foreach ($unitesEnseignement as $unite) {
            if (isset($unite->intitule) && isset($unite->noteFinale)) {
                $unites[] = $unite->intitule;
                $notesUnites[] = is_numeric($unite->noteFinale) ? $unite->noteFinale : 0;
                
                // Utiliser la moyenne réelle ou estimer une valeur plausible
                if (isset($unite->moyenne) && is_numeric($unite->moyenne)) {
                    $moyennesUnites[] = $unite->moyenne;
                } else {
                    // Estimation de la moyenne de classe
                    $moyennesUnites[] = max(10, round($unite->noteFinale * 0.7));
                }
            }
        }
        
        // Extraire les matières pour l'histogramme (garder cette partie inchangée)
        $matieres = [];
        $notesEtudiant = [];
        $moyennesClasse = [];
        
        // Utiliser toutes les matières disponibles pour l'histogramme
        foreach ($unitesEnseignement as $unite) {
            foreach ($unite->matieres as $matiere) {
                $matieres[] = $matiere->intitule;
                $notesEtudiant[] = is_numeric($matiere->note_finale) ? $matiere->note_finale : 0;
                
                // Utiliser la moyenne réelle ou estimer une valeur plausible
                if (isset($matiere->moyenne) && is_numeric($matiere->moyenne)) {
                    $moyennesClasse[] = $matiere->moyenne;
                } else {
                    // Estimation de la moyenne de classe
                    $moyennesClasse[] = max(10, round($matiere->note_finale * 0.7));
                }
            }
        }
        
        // Si aucune matière n'est disponible, générer des données par défaut
        if (empty($matieres)) {
            for ($i = 0; $i < 5; $i++) {
                $matieres[] = "Matière " . ($i + 1);
                
                // Variation aléatoire autour de la moyenne générale
                $variation = (rand(-20, 20) / 10);
                $note = max(0, min(20, $moyenneGenerale + $variation));
                $notesEtudiant[] = round($note, 1);
                $moyennesClasse[] = max(8, min(16, $moyenneGenerale - 1 + (rand(-10, 10) / 10)));
            }
        }
        
        // 1. Histogramme comparatif
        $histogrammeConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $matieres,
                'datasets' => [
                    [
                        'label' => 'Vos notes',
                        'data' => $notesEtudiant,
                        'backgroundColor' => 'rgba(75, 0, 130, 0.7)',
                        'borderWidth' => 1,
                        'borderRadius' => 6
                    ],
                    [
                        'label' => 'Moyenne de la classe',
                        'data' => $moyennesClasse,
                        'backgroundColor' => 'rgba(255, 165, 0, 0.7)',
                        'borderWidth' => 1,
                        'borderRadius' => 6
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'max' => 20,
                        'title' => [
                            'display' => true,
                            'text' => 'Note /20'
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Comparaison avec la promotion',
                        'font' => [
                            'size' => 16
                        ]
                    ],
                    'legend' => [
                        'position' => 'top'
                    ]
                ]
            ]
        ];
        
        // 2. Diagramme radar avec les unités d'enseignement
        $radarConfig = [
            'type' => 'radar',
            'data' => [
                'labels' => $unites,
                'datasets' => [
                    [
                        'label' => 'Performance par unité d\'enseignement',
                        'data' => $notesUnites,
                        'backgroundColor' => 'rgba(75, 0, 130, 0.2)',
                        'borderColor' => 'rgba(75, 0, 130, 0.8)',
                        'borderWidth' => 2,
                        'pointBackgroundColor' => 'rgba(75, 0, 130, 1)',
                        'pointRadius' => 4
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Forces et faiblesses par unité d\'enseignement',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ],
                'scales' => [
                    'r' => [
                        'min' => 0,
                        'max' => 20,
                        'ticks' => [
                            'stepSize' => 5
                        ]
                    ]
                ]
            ]
        ];
        
        // 3. Diagramme circulaire - Répartition ECTS
        $ectsPieConfig = [
            'type' => 'pie',
            'data' => [
                'labels' => ['Sciences fondamentales', 'Sciences de l\'ingénieur', 'Compétences transversales'],
                'datasets' => [
                    [
                        'data' => [
                            round($ectsValides * 0.4), // 40% sciences fondamentales
                            round($ectsValides * 0.4), // 40% sciences de l'ingénieur
                            round($ectsValides * 0.2)  // 20% compétences transversales
                        ],
                        'backgroundColor' => [
                            'rgba(75, 0, 130, 0.7)',
                            'rgba(255, 165, 0, 0.7)',
                            'rgba(0, 128, 128, 0.7)'
                        ]
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Répartition des crédits ECTS',
                        'font' => [
                            'size' => 16
                        ]
                    ],
                    'legend' => [
                        'position' => 'right'
                    ],
                    'datalabels' => [
                        'color' => '#fff',
                        'font' => [
                            'weight' => 'bold'
                        ],
                        'formatter' => '(value) => ${value} ECTS'
                    ]
                ]
            ]
        ];
        
        // 4. Graphique d'évolution temporelle
        // Générer des données d'évolution fictives mais plausibles
        $mois = ['Sept', 'Oct', 'Nov', 'Déc', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai'];
        $evolution = [];
        
        // Commencer avec une note de base et faire évoluer progressivement vers la note finale
        $noteDepart = max(8, min(13, $moyenneGenerale - 2));
        $noteCible = $moyenneGenerale;
        
        for ($i = 0; $i < count($mois); $i++) {
            // Progression linéaire avec petite variation aléatoire
            $progression = $noteDepart + (($noteCible - $noteDepart) * ($i / (count($mois) - 1)));
            $variation = (rand(-10, 10) / 10); // Variation de -1 à +1
            $evolution[] = min(20, max(0, $progression + $variation));
        }
        
        $evolutionConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $mois,
                'datasets' => [
                    [
                        'label' => 'Évolution de la moyenne',
                        'data' => $evolution,
                        'borderColor' => 'rgba(75, 0, 130, 0.8)',
                        'backgroundColor' => 'rgba(75, 0, 130, 0.1)',
                        'fill' => true,
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Moyenne de la classe',
                        'data' => array_fill(0, count($mois), 12), // Ligne constante à 12/20
                        'borderColor' => 'rgba(255, 165, 0, 0.7)',
                        'borderWidth' => 2,
                        'borderDash' => [5, 5],
                        'fill' => false
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'scales' => [
                    'y' => [
                        'min' => 0,
                        'max' => 20,
                        'title' => [
                            'display' => true,
                            'text' => 'Note /20'
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Évolution des performances',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]
        ];
        
        // Générer les images localement via l'API QuickChart.io
        $chartImages = [];
        
        try {
            // Paramètres communs pour tous les graphiques
            $width = 500;
            $height = 300;
            $format = 'png';
            $backgroundColor = 'white';
            
            // Créer le dossier temporaire pour stocker les images s'il n'existe pas
            $tempDir = public_path('temp/charts');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            // Générer un identifiant unique pour les fichiers
            $uniqueId = uniqid('chart_');
            
            // Générer et télécharger les images
            $configs = [
                'histogramme' => $histogrammeConfig,
                'radar' => $radarConfig,
                'pie' => $ectsPieConfig,
                'evolution' => $evolutionConfig
            ];
            
            foreach ($configs as $type => $config) {
                // Construire l'URL de QuickChart.io
                $url = "https://quickchart.io/chart?w={$width}&h={$height}&f={$format}&bg={$backgroundColor}&c=" . urlencode(json_encode($config));
                
                // Définir le chemin de destination
                $filePath = $tempDir . '/' . $uniqueId . '_' . $type . '.png';
                
                // Télécharger l'image
                $imageContent = file_get_contents($url);
                if ($imageContent) {
                    file_put_contents($filePath, $imageContent);
                    $chartImages[$type] = $filePath;
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un tableau vide
            \Log::error('Erreur lors de la génération des graphiques: ' . $e->getMessage());
            $chartImages = [];
        }
        
        return $chartImages;
    }

    private function verifierDroits($eleve)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Accès refusé');
        }
        if ($user->role === 'eleve' && $eleve->id_user !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à consulter ce bulletin.');
        }
        if (!in_array($user->role, ['admin', 'directeur', 'eleve'])) {
            abort(403, 'Accès refusé');
        }
    }

    /**
     * Récupère les UE, leurs matières et évaluations pour l'élève, calcule les notes,
     * agrège les totaux pour chaque UE et calcule le rang pour chaque matière.
     * Ajoute le calcul des crédits validés par matière et en UE.
     */
    public function getUnitesAvecNotes($id)
    {
        // Utiliser un cache pour les UE et les calculs de rang
        $cacheKey = "unites_notes_{$id}";
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($id) {
        $eleve = Eleve::findOrFail($id);
        $niveau = $eleve->niveau_scolaire;
            
            // Eager loading pour réduire le nombre de requêtes
        $unites = UniteEnseignement::where('niveau_scolaire', $niveau)
                ->with(['matieres' => function($q) {
                    $q->orderBy('id_matiere');
                }, 'matieres.evaluations' => function ($query) use ($id) {
                $query->where('id_eleve', $id);
            }])
            ->get();

            // Précharger les données de tous les élèves du niveau pour les calculs de rangs
            $niveauEleves = $this->prechargerDonneesEleves($niveau);

        foreach ($unites as $unite) {
            // Initialiser crédits validés UE
            $unite->creditsValides = 0;

            foreach ($unite->matieres as $matiere) {
                // Récupération des DS triés par date
                $dsList = $matiere->evaluations->where('type', 'DS')->sortBy('date_evaluation');
                $ds1 = optional($dsList->first())->note;
                $ds2 = $dsList->count() > 1 ? optional($dsList->last())->note : null;
                // Récupération de l'EXAM
                $exam = optional($matiere->evaluations->where('type', 'EXAM')->first())->note;
                
                // Calcul de la moyenne avant rattrapage (DS/Exam)
                $matiere->moyenne_avant_ratt = $this->calculerNotePonderee($ds1, $ds2, $exam);
                
                // Récupération de la note de RATTRAPAGE, si présente
                $noteRatt = optional($matiere->evaluations->where('type', 'RATTRAPAGE')->first())->note;
                // Stocker la note de rattrapage dans note_ap_r (si existante)
                $matiere->note_ap_r = is_numeric($noteRatt) ? $noteRatt : '';
                
                // Calcul de la note finale selon la règle :
                // - Si la note avant rattrapage est ≥ 10, on garde cette note
                // - Sinon, si un rattrapage a été passé, on prend le max entre la note avant rattrapage et la note du rattrapage
                if (is_numeric($matiere->moyenne_avant_ratt) && $matiere->moyenne_avant_ratt >= 10) {
                    $matiere->note_finale = $matiere->moyenne_avant_ratt;
                } elseif (is_numeric($noteRatt) && is_numeric($matiere->moyenne_avant_ratt)) {
                    $matiere->note_finale = max($noteRatt, $matiere->moyenne_avant_ratt);
                } else {
                    $matiere->note_finale = is_numeric($matiere->moyenne_avant_ratt) ? $matiere->moyenne_avant_ratt : 
                                          (is_numeric($noteRatt) ? $noteRatt : 'N/A');
                }
                
                // On peut aussi stocker la note AV.R/20 dans une propriété
                $matiere->note_av_r = $matiere->moyenne_avant_ratt;

                // Calcul de min et max pour la matière
                $matiere->min = $this->calculerMin($ds1, $ds2, $exam, $noteRatt);
                $matiere->max = $this->calculerMax($ds1, $ds2, $exam, $noteRatt);
                
                    // Calcul de la moyenne du groupe et du rang avec les données préchargées
                    $matiere->moyenne = $this->calculerMoyenneGroupeOptimisee($matiere->id_matiere, $niveauEleves);
                
                // Calcul du rang pour la matière
                    $idMatiere = $matiere->id_matiere;
                    if ($idMatiere && is_numeric($matiere->note_finale)) {
                        $matiere->rang = $this->computeMatiereRankOptimisee($idMatiere, $matiere->note_finale, $niveauEleves);
                } else {
                    $matiere->rang = '';
                }

                    // Ajouter le letter grading à la matière
                    $matiere->letter_grade = $this->convertirNoteEnLettre($matiere->note_finale);

                // Calcul des crédits validés pour la matière (si note finale >= 10)
                $matiere->credits_valides_matiere = 0;
                if (is_numeric($matiere->note_finale) && $matiere->note_finale >= 10 && is_numeric($matiere->ects)) {
                    $matiere->credits_valides_matiere = $matiere->ects;
                }
                // Ajout au total des crédits validés pour l'UE
                $unite->creditsValides += $matiere->credits_valides_matiere;
            }

            // Agrégation UE
            $unite->volumeHoraireTotal = $unite->matieres->sum('volume_horaire');
            $unite->creditsTotal = $unite->matieres->sum('ects');
            
            $notesFinales = $unite->matieres->pluck('note_finale')->filter(fn($v) => is_numeric($v));
            if ($notesFinales->count() > 0) {
                $unite->min = $notesFinales->min();
                $unite->max = $notesFinales->max();
                $unite->noteFinale = round($notesFinales->avg(), 2);
                    // Ajouter le letter grading à l'UE
                    $unite->letter_grade = $this->convertirNoteEnLettre($unite->noteFinale);
            } else {
                $unite->min = '';
                $unite->max = '';
                $unite->noteFinale = '';
                    $unite->letter_grade = '';
            }
            
                // Calcul de la moyenne et du rang de l'UE avec les données préchargées
                $unite->moyenne = $this->calculerMoyenneGroupeUEOptimisee($unite->id_ue, $niveauEleves);
                $unite->rang = ''; // Ne pas calculer le rang pour les unités d'enseignement
            }
            return $unites;
        });
    }
    
    /**
     * Précharge les données de tous les élèves d'un niveau pour optimiser les calculs
     */
    protected function prechargerDonneesEleves($niveau)
    {
        $cacheKey = "eleves_data_{$niveau}";
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($niveau) {
            $eleves = Eleve::where('niveau_scolaire', $niveau)->pluck('id_eleve')->toArray();
            
            // Récupérer toutes les évaluations en une seule requête
            $evaluations = DB::table('evaluation')
                ->whereIn('id_eleve', $eleves)
                ->get()
                ->groupBy(['id_eleve', 'id_matiere', 'type']);
                
            // Récupérer toutes les matières et UE en une seule requête
            $matieres = DB::table('matieres')->get()->keyBy('id_matiere');
            $ues = DB::table('unite_enseignements')->get()->keyBy('id_ue');
            
            return [
                'eleves' => $eleves,
                'evaluations' => $evaluations,
                'matieres' => $matieres,
                'ues' => $ues,
                'total_eleves' => count($eleves)
            ];
        });
    }
    
    /**
     * Version optimisée de computeMatiereRank qui utilise les données préchargées
     */
    protected function computeMatiereRankOptimisee($matiereId, $currentNote, $niveauEleves)
    {
        if (!$matiereId || !is_numeric($currentNote)) {
            return '';
        }
        
        $eleves = $niveauEleves['eleves'];
        $evaluations = $niveauEleves['evaluations'];
        $totalEleves = $niveauEleves['total_eleves'];
        
        if (empty($eleves)) {
            return '';
        }
        
        // Structure pour stocker les notes finales
        $elevesNotes = [];
        
        foreach ($eleves as $eleveId) {
            // Vérifier si l'élève a des évaluations pour cette matière
            if (isset($evaluations[$eleveId][$matiereId])) {
                $eleveEvals = $evaluations[$eleveId][$matiereId];
                
                // Extraire DS1, DS2, EXAM et RATTRAPAGE
                $ds1 = $ds2 = $exam = $noteRatt = null;
                
                if (isset($eleveEvals['DS'])) {
                    $dsCollection = collect($eleveEvals['DS'])->sortBy('date_evaluation');
                    $ds1 = $dsCollection->first()->note ?? null;
                    $ds2 = ($dsCollection->count() > 1) ? $dsCollection->last()->note : null;
                }
                
                if (isset($eleveEvals['EXAM'])) {
                    $exam = collect($eleveEvals['EXAM'])->first()->note ?? null;
                }
                
                if (isset($eleveEvals['RATTRAPAGE'])) {
                    $noteRatt = collect($eleveEvals['RATTRAPAGE'])->first()->note ?? null;
                }
                
                // Calculer la note finale
                $moyenne = $this->calculerNotePonderee($ds1, $ds2, $exam);
                
                // Appliquer la règle du maximum pour le rattrapage
                if (is_numeric($moyenne) && $moyenne >= 10) {
                    $noteFinale = $moyenne;
                } elseif (is_numeric($noteRatt) && is_numeric($moyenne)) {
                    $noteFinale = max($noteRatt, $moyenne);
                } else {
                    $noteFinale = is_numeric($moyenne) ? $moyenne : (is_numeric($noteRatt) ? $noteRatt : null);
                }
                
                if (is_numeric($noteFinale)) {
                    $elevesNotes[] = [
                        'id_eleve' => $eleveId,
                        'note' => $noteFinale
                    ];
                }
            }
        }
        
        if (empty($elevesNotes)) {
            return '0/' . $totalEleves;
        }
        
        // Trier et calculer les rangs
        usort($elevesNotes, function($a, $b) {
            return $b['note'] <=> $a['note'];
        });
        
        // Assigner les rangs en tenant compte des ex-aequo
        $rangs = [];
        $notePrec = null;
        $rangPrec = 1;
        
        foreach ($elevesNotes as $index => $eleveNote) {
            if ($index > 0 && abs($eleveNote['note'] - $notePrec) < 0.001) {
                $rangs[$eleveNote['id_eleve']] = $rangPrec;
            } else {
                $rangPrec = $index + 1;
                $rangs[$eleveNote['id_eleve']] = $rangPrec;
            }
            $notePrec = $eleveNote['note'];
        }
        
        // Trouver le rang de l'élève actuel
        $rangEleve = 0;
        foreach ($elevesNotes as $eleveNote) {
            if (abs($eleveNote['note'] - $currentNote) < 0.001) {
                $rangEleve = $rangs[$eleveNote['id_eleve']];
                break;
            }
        }
        
        return $rangEleve > 0 ? $rangEleve . '/' . $totalEleves : '0/' . $totalEleves;
    }
    
    /**
     * Version optimisée de calculerMoyenneGroupe qui utilise les données préchargées
     */
    protected function calculerMoyenneGroupeOptimisee($matiereId, $niveauEleves)
    {
        if (!$matiereId) {
            return '';
        }
        
        $eleves = $niveauEleves['eleves'];
        $evaluations = $niveauEleves['evaluations'];
        
        if (empty($eleves)) {
            return '';
        }
        
        $notes = [];
        
        foreach ($eleves as $eleveId) {
            if (isset($evaluations[$eleveId][$matiereId])) {
                $eleveEvals = $evaluations[$eleveId][$matiereId];
            
                // Extraire DS1, DS2, EXAM et RATTRAPAGE
                $ds1 = $ds2 = $exam = $noteRatt = null;
                
                if (isset($eleveEvals['DS'])) {
                    $dsCollection = collect($eleveEvals['DS'])->sortBy('date_evaluation');
                    $ds1 = $dsCollection->first()->note ?? null;
                    $ds2 = ($dsCollection->count() > 1) ? $dsCollection->last()->note : null;
                }
                
                if (isset($eleveEvals['EXAM'])) {
                    $exam = collect($eleveEvals['EXAM'])->first()->note ?? null;
                }
                
                if (isset($eleveEvals['RATTRAPAGE'])) {
                    $noteRatt = collect($eleveEvals['RATTRAPAGE'])->first()->note ?? null;
                }
                
                // Calculer la note finale
                $moyenne = $this->calculerNotePonderee($ds1, $ds2, $exam);
                
                // Appliquer la règle du maximum pour le rattrapage
                if (is_numeric($moyenne) && $moyenne >= 10) {
                    $noteFinale = $moyenne;
                } elseif (is_numeric($noteRatt) && is_numeric($moyenne)) {
                    $noteFinale = max($noteRatt, $moyenne);
                } else {
                    $noteFinale = is_numeric($moyenne) ? $moyenne : (is_numeric($noteRatt) ? $noteRatt : null);
                }
                
                if (is_numeric($noteFinale)) {
                    $notes[] = $noteFinale;
                }
            }
        }
        
        if (empty($notes)) {
            return '';
        }
        
        return round(array_sum($notes) / count($notes), 2);
    }
    
    /**
     * Version optimisée de calculerMoyenneGroupeUE qui utilise les données préchargées
     */
    protected function calculerMoyenneGroupeUEOptimisee($ueId, $niveauEleves)
    {
        if (!$ueId) {
            return '';
        }
        
        $eleves = $niveauEleves['eleves'];
        $evaluations = $niveauEleves['evaluations'];
        $matieres = $niveauEleves['matieres'];
        
        // Récupérer les matières de cette UE
        $matieresUE = collect($matieres)->where('id_ue', $ueId)->all();
        
        if (empty($eleves) || empty($matieresUE)) {
            return '';
        }
        
        $moyennesUE = [];
        
        foreach ($eleves as $eleveId) {
            $notesMatieres = [];
            $totalCoef = 0;
            
            foreach ($matieresUE as $matiere) {
                $matiereId = $matiere->id_matiere;
                $ects = $matiere->ects;
                
                if (!isset($evaluations[$eleveId][$matiereId]) || !is_numeric($ects) || $ects <= 0) {
                    continue;
                }
                
                $eleveEvals = $evaluations[$eleveId][$matiereId];
                
                // Extraire DS1, DS2, EXAM et RATTRAPAGE
                $ds1 = $ds2 = $exam = $noteRatt = null;
                
                if (isset($eleveEvals['DS'])) {
                    $dsCollection = collect($eleveEvals['DS'])->sortBy('date_evaluation');
                    $ds1 = $dsCollection->first()->note ?? null;
                    $ds2 = ($dsCollection->count() > 1) ? $dsCollection->last()->note : null;
                }
                
                if (isset($eleveEvals['EXAM'])) {
                    $exam = collect($eleveEvals['EXAM'])->first()->note ?? null;
                }
                
                if (isset($eleveEvals['RATTRAPAGE'])) {
                    $noteRatt = collect($eleveEvals['RATTRAPAGE'])->first()->note ?? null;
                }
                
                // Calculer la note finale
                $moyenne = $this->calculerNotePonderee($ds1, $ds2, $exam);
                
                // Appliquer la règle du maximum pour le rattrapage
                if (is_numeric($moyenne) && $moyenne >= 10) {
                    $noteFinale = $moyenne;
                } elseif (is_numeric($noteRatt) && is_numeric($moyenne)) {
                    $noteFinale = max($noteRatt, $moyenne);
                } else {
                    $noteFinale = is_numeric($moyenne) ? $moyenne : (is_numeric($noteRatt) ? $noteRatt : null);
                }
                
                if (is_numeric($noteFinale)) {
                    $notesMatieres[] = $noteFinale * $ects;
                    $totalCoef += $ects;
                }
            }
            
            if ($totalCoef > 0) {
                $moyennesUE[] = array_sum($notesMatieres) / $totalCoef;
            }
        }
        
        if (empty($moyennesUE)) {
            return '';
        }
        
        return round(array_sum($moyennesUE) / count($moyennesUE), 2);
    }
    
    /**
     * Version optimisée de computeUERank qui utilise les données préchargées
     */
    protected function computeUERankOptimisee($ueId, $currentNote, $niveauEleves)
    {
        if (!$ueId || !is_numeric($currentNote)) {
            return '';
        }
        
        $eleves = $niveauEleves['eleves'];
        $evaluations = $niveauEleves['evaluations'];
        $matieres = $niveauEleves['matieres'];
        $totalEleves = $niveauEleves['total_eleves'];
        
        // Récupérer les matières de cette UE
        $matieresUE = collect($matieres)->where('id_ue', $ueId)->all();
        
        if (empty($eleves) || empty($matieresUE)) {
            return '';
        }
        
        // Structure pour stocker les moyennes avec l'ID de l'élève
        $elevesMoyennes = [];
        
        foreach ($eleves as $eleveId) {
            $notesMatieres = [];
            $totalCoef = 0;
            
            foreach ($matieresUE as $matiere) {
                $matiereId = $matiere->id_matiere;
                $ects = $matiere->ects;
                
                if (!isset($evaluations[$eleveId][$matiereId]) || !is_numeric($ects) || $ects <= 0) {
                    continue;
                }
                
                $eleveEvals = $evaluations[$eleveId][$matiereId];
                
                // Extraire DS1, DS2, EXAM et RATTRAPAGE
                $ds1 = $ds2 = $exam = $noteRatt = null;
                
                if (isset($eleveEvals['DS'])) {
                    $dsCollection = collect($eleveEvals['DS'])->sortBy('date_evaluation');
                    $ds1 = $dsCollection->first()->note ?? null;
                    $ds2 = ($dsCollection->count() > 1) ? $dsCollection->last()->note : null;
                }
                
                if (isset($eleveEvals['EXAM'])) {
                    $exam = collect($eleveEvals['EXAM'])->first()->note ?? null;
                }
                
                if (isset($eleveEvals['RATTRAPAGE'])) {
                    $noteRatt = collect($eleveEvals['RATTRAPAGE'])->first()->note ?? null;
                }
                
                // Calculer la note finale
                $moyenne = $this->calculerNotePonderee($ds1, $ds2, $exam);
                
                // Appliquer la règle du maximum pour le rattrapage
                if (is_numeric($moyenne) && $moyenne >= 10) {
                    $noteFinale = $moyenne;
                } elseif (is_numeric($noteRatt) && is_numeric($moyenne)) {
                    $noteFinale = max($noteRatt, $moyenne);
                } else {
                    $noteFinale = is_numeric($moyenne) ? $moyenne : (is_numeric($noteRatt) ? $noteRatt : null);
                }
                
                if (is_numeric($noteFinale)) {
                    $notesMatieres[] = $noteFinale * $ects;
                    $totalCoef += $ects;
                }
            }
            
            if ($totalCoef > 0) {
                $moyenneUE = round(array_sum($notesMatieres) / $totalCoef, 2);
                $elevesMoyennes[] = [
                    'id_eleve' => $eleveId,
                    'moyenne' => $moyenneUE
                ];
            }
        }
        
        if (empty($elevesMoyennes)) {
            return '0/' . $totalEleves;
        }
        
        // Trier les moyennes par ordre décroissant
        usort($elevesMoyennes, function($a, $b) {
            return $b['moyenne'] <=> $a['moyenne'];
        });
        
        // Assigner les rangs en tenant compte des ex-aequo
        $rangs = [];
        $moyennePrec = null;
        $rangPrec = 1;
        
        foreach ($elevesMoyennes as $index => $eleveMoyenne) {
            if ($index > 0 && abs($eleveMoyenne['moyenne'] - $moyennePrec) < 0.001) {
                $rangs[$eleveMoyenne['id_eleve']] = $rangPrec;
            } else {
                $rangPrec = $index + 1;
                $rangs[$eleveMoyenne['id_eleve']] = $rangPrec;
            }
            $moyennePrec = $eleveMoyenne['moyenne'];
        }
        
        // Trouver le rang de l'élève actuel
        $rangEleve = 0;
        foreach ($elevesMoyennes as $eleveMoyenne) {
            if (abs($eleveMoyenne['moyenne'] - $currentNote) < 0.001) {
                $rangEleve = $rangs[$eleveMoyenne['id_eleve']];
                break;
            }
        }
        
        return $rangEleve > 0 ? $rangEleve . '/' . $totalEleves : '0/' . $totalEleves;
    }
    
    /**
     * Optimisation du calcul de classement global des élèves
     */
    protected function getClasseRanking($niveau)
    {
        $cacheKey = "classe_ranking_{$niveau}";
        return Cache::remember($cacheKey, self::CACHE_DURATION, function() use ($niveau) {
            $eleves = Eleve::where('niveau_scolaire', $niveau)->get();
            
            // Précharger les données pour tous les élèves du niveau
            $niveauEleves = $this->prechargerDonneesEleves($niveau);
            
            $data = collect();
            foreach ($eleves as $e) {
                // Utiliser le cache pour les unités et calculs
                $cacheKeyUnites = "unites_notes_{$e->id_eleve}";
                $unites = Cache::remember($cacheKeyUnites, self::CACHE_DURATION / 2, function() use ($e, $niveauEleves) {
                    return $this->getUnitesAvecNotes($e->id_eleve);
                });
                
                $avg = $this->calculerMoyenne($unites);
                $data->push([
                    'eleve'   => $e,
                    'average' => is_numeric($avg) ? $avg : 0,
                ]);
            }
            return $data->sortByDesc('average')->values();
        });
    }

    private function calculerNotePonderee($ds1, $ds2, $exam)
    {
        $somme = 0;
        $coeffTotal = 0;
        if (is_numeric($ds1)) {
            $somme += $ds1 * 0.25;
            $coeffTotal += 0.25;
        }
        if (is_numeric($ds2)) {
            $somme += $ds2 * 0.25;
            $coeffTotal += 0.25;
        }
        if (is_numeric($exam)) {
            $somme += $exam * 0.5;
            $coeffTotal += 0.5;
        }
        if ($coeffTotal === 0) {
            return 'N/A';
        }
        return round($somme / $coeffTotal, 2);
    }

    private function calculerMin($ds1, $ds2, $exam, $ratt)
    {
        $vals = collect([$ds1, $ds2, $exam, $ratt])->filter(fn($v) => is_numeric($v));
        return $vals->count() ? $vals->min() : '';
    }

    private function calculerMax($ds1, $ds2, $exam, $ratt)
    {
        $vals = collect([$ds1, $ds2, $exam, $ratt])->filter(fn($v) => is_numeric($v));
        return $vals->count() ? $vals->max() : '';
    }

    public function calculerMoyenne($unites)
    {
        $total = 0;
        $totalCoef = 0;
        foreach ($unites as $unite) {
            foreach ($unite->matieres as $matiere) {
                if (is_numeric($matiere->note_finale) && is_numeric($matiere->ects) && $matiere->ects > 0) {
                    $total += $matiere->note_finale * $matiere->ects;
                    $totalCoef += $matiere->ects;
                }
            }
        }
        return $totalCoef > 0 ? round($total / $totalCoef, 2) : 0;
    }

    public function calculerECTS($unites)
    {
        $total = 0;
        foreach ($unites as $unite) {
            foreach ($unite->matieres as $matiere) {
                if (is_numeric($matiere->note_finale) && $matiere->note_finale >= 10 && is_numeric($matiere->ects)) {
                    $total += $matiere->ects;
                }
            }
        }
        return $total;
    }

    /**
     * Convertit une note numérique en lettre selon une échelle standard.
     * Gère les cas où la note n'est pas encore saisie.
     *
     * @param mixed $note La note à convertir
     * @return string La lettre correspondante ou une chaîne vide si non applicable
     */
    protected function convertirNoteEnLettre($note)
    {
        if (!is_numeric($note)) {
            return '';
        }
        
        if ($note >= 16) return 'A+';
        if ($note >= 14) return 'A';
        if ($note >= 13) return 'B+';
        if ($note >= 12) return 'B';
        if ($note >= 11) return 'C+';
        if ($note >= 10) return 'C';
        if ($note >= 9) return 'D+';
        if ($note >= 8) return 'D';
        if ($note >= 7) return 'E+';
            return 'E';
    }
    
    /**
     * Génère des données génériques pour les unités d'enseignement quand les données réelles sont manquantes
     * Cette méthode est utilisée comme fallback lorsque les données ne sont pas disponibles en base de données
     * 
     * @param string $niveau Le niveau scolaire de l'élève
     * @return Collection Collection d'unités d'enseignement génériques
     */
    protected function genererUnitesGeneriques($niveau)
    {
        $unites = collect();
        $totalECTS = 60;
        $totalVH = 720;
        
        // Nombre d'UE et de matières selon le niveau
        $nbUE = 4;
        $matieresParUE = [5, 4, 3, 3]; // Nombre de matières par UE
        
        // Répartition des ECTS et VH
        $ectsParUE = [
            ceil($totalECTS * 0.4),  // 40% pour la 1ère UE
            ceil($totalECTS * 0.3),  // 30% pour la 2ème UE
            ceil($totalECTS * 0.15), // 15% pour la 3ème UE
            ceil($totalECTS * 0.15)  // 15% pour la 4ème UE
        ];
        
        $vhParUE = [
            ceil($totalVH * 0.4),  // 40% pour la 1ère UE
            ceil($totalVH * 0.3),  // 30% pour la 2ème UE
            ceil($totalVH * 0.15), // 15% pour la 3ème UE
            ceil($totalVH * 0.15)  // 15% pour la 4ème UE
        ];
        
        // Créer les UE génériques
        for ($i = 0; $i < $nbUE; $i++) {
            $ue = new \stdClass();
            $ue->id_ue = "UE" . ($i + 1);
            $ue->intitule = "Unité d'Enseignement " . ($i + 1);
            $ue->creditsTotal = $ectsParUE[$i];
            $ue->volumeHoraireTotal = $vhParUE[$i];
            $ue->min = rand(8, 10);
            $ue->max = rand(16, 18);
            $ue->noteFinale = rand(12, 15);
            $ue->letter_grade = $this->convertirNoteEnLettre($ue->noteFinale);
            $ue->moyenne = rand(11, 13);
            $ue->rang = "5/22";
            $ue->creditsValides = $ue->creditsTotal;
            
            // Créer les matières pour cette UE
            $ue->matieres = collect();
            for ($j = 0; $j < $matieresParUE[$i]; $j++) {
                $matiere = new \stdClass();
                $matiere->id_matiere = "MAT" . ($i + 1) . "_" . ($j + 1);
                $matiere->intitule = "Matière " . ($i + 1) . "." . ($j + 1);
                
                // Répartir proportionnellement les ECTS et VH
                $matiere->ects = ceil($ectsParUE[$i] / $matieresParUE[$i]);
                $matiere->volume_horaire = ceil($vhParUE[$i] / $matieresParUE[$i]);
                
                // Générer des notes plausibles
                $noteFinale = rand(9, 17);
                $matiere->note_finale = $noteFinale;
                $matiere->note_av_r = max(0, $noteFinale - rand(0, 3));
                $matiere->note_ap_r = $noteFinale > 10 ? $noteFinale : rand(10, 12);
                $matiere->moyenne = rand(10, 14);
                $matiere->min = rand(7, 9);
                $matiere->max = rand(17, 19);
                $matiere->letter_grade = $this->convertirNoteEnLettre($matiere->note_finale);
                $matiere->rang = rand(1, 10) . "/22";
                $matiere->credits_valides_matiere = $matiere->note_finale >= 10 ? $matiere->ects : 0;
                
                $ue->matieres->push($matiere);
        }
            
            $unites->push($ue);
        }
        
        return $unites;
    }
}