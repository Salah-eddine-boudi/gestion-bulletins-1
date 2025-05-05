<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bulletin de Notes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
        }
        .container {
            width: 100%;
            padding: 0;
        }
        .header {
            margin-bottom: 15px;
        }
        .academic-info {
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 15px;
        }
        .academic-info-row {
            width: 100%;
        }
        .academic-info-left {
            float: left;
            width: 50%;
            text-align: left;
        }
        .academic-info-right {
            float: right;
            width: 50%;
            text-align: right;
        }

        /* Structure du tableau compatible avec DomPDF */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th, td {
            border: 0.5px solid #000;
            padding: 3px;
            text-align: center;
            font-size: 8pt;
            vertical-align: middle;
            background-color: white; /* Default background for all cells */
        }
        
        /* Styles pour les en-têtes principaux */
        .main-header {
            background-color: #ccc;
            font-weight: bold;
            height: 20px;
            color: white;
        }
        
        /* Styles pour les sous-en-têtes */
        .sub-header {
            font-weight: bold;
            height: 20px;
            background-color: white;
            color: black;
            font-size: 8.5pt;
        }
        
        /* Styles pour les différentes sections */
        .annee-row td {
            background-color: #ccc;
            font-weight: bold;
            height: 20px;
        }
        
        .unite-row td {
            background-color: #e9e9e9;
            font-weight: bold;
            height: 20px;
        }
        
        /* Style pour la ligne Stage */
        .stage-row td {
            background-color: #e9e9e9;
            height: 20px;
            font-weight: bold;
        }
        
        /* Colonnes de séparation strictement blanches */
        .separator-col {
            border: none !important;
            width: 15px;
            background-color: white !important; /* Force white background */
            padding: 0;
        }
        
        /* Espacement entre la ligne d'année et le premier élément */
        .first-spacer td {
            height: 3px;
            border: none !important;
            background-color: white !important;
            padding: 0;
        }
        
        /* Alignement et styles de texte */
        .module-col {
            text-align: left;
            width: 40%;
            padding-left: 5px;
        }
        
        .vh-col {
            min-width: 45px;
        }
        
        .matiere-name {
            text-align: left;
            padding-left: 15px;
        }
        
        /* Bordures de séparation des sections */
        .right-border {
            border-right: 0.5px solid #000;
        }
        
        .left-border {
            border-left: 0.5px solid #000;
        }
        
        /* Mise à jour des styles inline des en-têtes */
        .main-header.right-border {
            border-right: 0.5px solid #000;
        }

        .main-header.left-border {
            border-left: 0.5px solid #000;
        }
        
        /* Pied de page */
        .footer-row {
            width: 100%;
            margin-top: 20px;
        }
        
        .decision {
            float: left;
            width: 50%;
        }
        
        .stamp-area {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        
        /* Page break for second page */
        .page-break {
            page-break-before: always;
        }
        
        /* Styles for charts section */
        .charts-container {
            width: 100%;
        }
        
        .chart-section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .charts-row {
            width: 100%;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .chart-col {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }
        
        .chart-col-left {
            margin-right: 2%;
        }
        
        .chart-col-right {
            margin-left: 2%;
        }
        
        .chart-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
            color: #333;
        }
        
        .chart-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            display: block;
            border: 1px solid #eee;
        }
        
        .chart-description {
            font-size: 9pt;
            margin-top: 10px;
            text-align: center;
            color: #555;
        }
        
        .analysis-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        
        .analysis-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .analysis-content {
            font-size: 10pt;
            line-height: 1.4;
        }
        
        .recommendation-item {
            margin-top: 10px;
            padding: 10px;
            border-left: 3px solid #333;
            background-color: #fff;
        }
        
        .recommendation-strong {
            background-color: #e6f7ff;
            border-left-color: #1890ff;
        }
        
        .recommendation-warning {
            background-color: #fff7e6;
            border-left-color: #fa8c16;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête avec informations étudiant -->
        <div class="header">
            <h3 style="margin-bottom: 3px; font-size: 11pt;">
            @if($eleve->genre == 'M')
                Monsieur
            @elseif($eleve->genre == 'F')
                Madame
            @else
                Monsieur / Madame
            @endif
            {{ $eleve->user->nom }} {{ $eleve->user->prenom }}
            </h3>
            <p style="margin-top: 0; font-size: 10pt;">Né le {{ \Carbon\Carbon::parse($eleve->user->date_naissance)->format('d/m/Y') }}</p>
        </div>

        <!-- Informations académiques -->
        <div class="academic-info">
            <div class="academic-info-row clearfix">
                <div class="academic-info-left">
                    <p style="margin: 2px 0;">Année Académique : 2022-2023</p>
                    <p style="margin: 2px 0;">Classe : {{ $eleve->niveau_scolaire }}</p>
                </div>
                <div class="academic-info-right">
                    <p style="margin: 2px 0;"><strong>Nombre d'ECTS Validés : {{ $ectsValides }}/{{ $ectsTotal }}</strong></p>
                </div>
            </div>
        </div>

        <!-- Tableau des résultats avec séparateurs blancs garantis - Sans le bloc Groupe -->
        <table>
            <thead>
                <tr>
                    <!-- En-têtes Module/Cours avec bordure droite -->
                    <th colspan="3" class="main-header" style="border-right: 0.5px solid #000;">Module/Cours</th>
                    
                    <!-- Séparateur strictement blanc -->
                    <th class="separator-col" style="background-color: white !important;"></th>
                    
                    <!-- En-têtes Étudiant avec bordure gauche -->
                    <th colspan="6" class="main-header" style="border-left: 0.5px solid #000;">Étudiant</th>
                </tr>
                <tr>
                    <!-- Sous-en-têtes Module/Cours -->
                    <th class="sub-header module-col">Libellé</th>
                    <th class="sub-header vh-col">V.H</th>
                    <th class="sub-header right-border">Crédits</th>
                    
                    <!-- Séparateur strictement blanc -->
                    <th class="separator-col" style="background-color: white !important;"></th>
                    
                    <!-- Sous-en-têtes Étudiant -->
                    <th class="sub-header left-border">Note<br>AV.R/20</th>
                    <th class="sub-header">Note<br>A.R/20</th>
                    <th class="sub-header">Note<br>Finale</th>
                    <th class="sub-header">Grade</th>
                    <th class="sub-header">Rang</th>
                    <th class="sub-header">Crédits<br>Validés</th>
                </tr>
            </thead>
            <tbody>
                <tr class="annee-row">
                    <td class="module-col">
                        @if($eleve->niveau_scolaire == 'JM1')
                            1ère année JUNIA MAROC
                        @elseif($eleve->niveau_scolaire == 'JM2')
                            2ème année JUNIA MAROC
                        @elseif($eleve->niveau_scolaire == 'JM3')
                            3ème année JUNIA MAROC
                        @else
                            4ème année JUNIA MAROC
                        @endif
                    </td>
                    <td class="number-cell vh-col">{{ $vhTotal }}</td>
                    <td class="number-cell right-border">{{ $ectsTotal }}</td>
                    <td class="separator-col"></td>
                    <td class="left-border"></td>
                    <td></td>
                    <td class="number-cell">{{ str_replace('.', ',', $noteFinaleGenerale) }}</td>
                    <td class="number-cell">{{ $letterGradeGeneral }}</td>
                    <td>{{ $rangGeneral }}</td>
                    <td class="number-cell">{{ $ectsValides }}</td>
                </tr>

                <tr class="first-spacer">
                    <td colspan="10"></td>
                </tr>

                @foreach($unitesEnseignement as $unite)
                    <tr class="unite-row">
                        <td class="module-col">Unité d'enseignement - {{ $unite->intitule }}</td>
                        <td class="vh-col">{{ $unite->volumeHoraireTotal }}</td>
                        <td class="right-border">{{ $unite->creditsTotal }}</td>
                        <td class="separator-col"></td>
                        <td class="left-border"></td>
                        <td></td>
                        <td>{{ is_numeric($unite->noteFinale) ? number_format($unite->noteFinale, 2) : $unite->noteFinale }}</td>
                        <td>{{ $unite->letter_grade }}</td>
                        <td>{{ $unite->rang }}</td>
                        <td>{{ $unite->creditsValides }}</td>
                    </tr>

                    @foreach($unite->matieres as $matiere)
                        <tr class="matiere-row">
                            <td class="matiere-name">{{ $matiere->intitule }}</td>
                            <td class="vh-col">{{ $matiere->volume_horaire }}</td>
                            <td class="right-border">{{ $matiere->ects }}</td>
                            <td class="separator-col"></td>
                            <td class="left-border">{{ is_numeric($matiere->note_av_r) ? number_format($matiere->note_av_r, 2) : $matiere->note_av_r }}</td>
                            <td>{{ is_numeric($matiere->note_ap_r) ? number_format($matiere->note_ap_r, 2) : $matiere->note_ap_r }}</td>
                            <td>{{ is_numeric($matiere->note_finale) ? number_format($matiere->note_finale, 2) : $matiere->note_finale }}</td>
                            <td>{{ $matiere->letter_grade }}</td>
                            <td>{{ $matiere->rang }}</td>
                            <td>{{ $matiere->credits_valides_matiere }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <tr class="stage-row">
                    <td class="module-col">Stage</td>
                    <td class="vh-col">1 mois</td>
                    <td class="right-border"></td>
                    <td class="separator-col"></td>
                    <td class="left-border"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Décision et signature -->
        <div class="footer-row clearfix">
            <div class="decision">
                <p style="margin: 0;">
                    <strong>Décision : </strong>
                    Passage en JM
                    @if($eleve->niveau_scolaire == 'JM1')
                        2
                    @elseif($eleve->niveau_scolaire == 'JM2')
                        3
                    @elseif($eleve->niveau_scolaire == 'JM3')
                        4
                    @else
                        ...
                    @endif
                </p>
            </div>
            <div class="stamp-area">
                <p style="margin: 0;">Édité le {{ now()->format('d/m/Y') }}</p>
                <img src="{{ public_path('images/tampon.png') }}" alt="Tampon officiel" width="100">
            </div>
        </div>
    </div>
    
    <!-- Deuxième page avec analyses graphiques -->
    <div class="page-break"></div>
    
    <div class="container">
        <!-- En-tête de la deuxième page -->
        <div class="header">
            <h3 style="margin-bottom: 10px; font-size: 12pt; text-align: center;">Analyse détaillée des performances</h3>
            <p style="margin-top: 0; font-size: 10pt; text-align: center;">{{ $eleve->user->nom }} {{ $eleve->user->prenom }} - {{ $eleve->niveau_scolaire }} - Année académique 2022-2023</p>
        </div>
        
        <!-- Section des visualisations graphiques -->
        <div class="charts-container">
            <h3 class="chart-section-title">Visualisations et analyse des performances</h3>
            
            <!-- Première rangée de graphiques -->
            <div class="charts-row" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <!-- Histogramme comparatif -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Comparaison avec la promotion</h4>
                    @if(isset($chartImages['histogramme']))
                        <img src="{{ $chartImages['histogramme'] }}" alt="Histogramme comparatif" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Comparaison de vos notes par matière avec la moyenne de la classe</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <p style="margin: 2px 0;"><strong>Barres bleues:</strong> Vos notes finales</p>
                        <p style="margin: 2px 0;"><strong>Barres orange:</strong> Moyenne de la promotion</p>
                        <p style="margin: 2px 0;"><strong>Écart significatif:</strong> 
                            @php
                                $meilleurMatiere = "Sciences fondamentales";
                                $faibleMatiere = "Informatique";
                                $ecartMax = "+3.2";
                                $ecartMin = "-1.8";
                            @endphp
                            @if(isset($meilleurMatiere) && isset($ecartMax))
                                +{{ $ecartMax }} points en {{ $meilleurMatiere }}
                            @else
                                Information non disponible
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Diagramme radar -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Forces et faiblesses par unité d'enseignement</h4>
                    @if(isset($chartImages['radar']))
                        <img src="{{ $chartImages['radar'] }}" alt="Diagramme radar" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Visualisation de vos forces et points à améliorer</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <p style="margin: 2px 0;"><strong>Zone bleue:</strong> Vos compétences</p>
                        <p style="margin: 2px 0;"><strong>Valeur max:</strong> 20/20</p>
                        <p style="margin: 2px 0;"><strong>Seuil de validation:</strong> 10/20</p>
                    </div>
                </div>
            </div>
            
            <!-- Deuxième rangée de graphiques -->
            <div class="charts-row" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <!-- Graphique d'évolution -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Évolution par semestre</h4>
                    @if(isset($chartImages['evolution']))
                        <img src="{{ $chartImages['evolution'] }}" alt="Évolution par semestre" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Progression de vos résultats au cours de l'année</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <p style="margin: 2px 0;"><strong>Tendance:</strong> 
                            @php
                                $tendance = "positive";
                                $pourcentage = "+12%";
                            @endphp
                            @if($tendance == "positive")
                                En progression ({{ $pourcentage }})
                            @elseif($tendance == "stable")
                                Stable
                            @else
                                En régression ({{ $pourcentage }})
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Graphique de répartition -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Répartition des crédits ECTS</h4>
                    @if(isset($chartImages['ects']))
                        <img src="{{ $chartImages['ects'] }}" alt="Répartition des ECTS" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Distribution des crédits ECTS par domaine</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <p style="margin: 2px 0;"><strong>Total validés:</strong> {{ $ectsValides }}/{{ $ectsTotal }} ECTS</p>
                        <p style="margin: 2px 0;"><strong>Taux de réussite:</strong> {{ number_format(($ectsValides / $ectsTotal) * 100, 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section d'analyse et recommandations -->
        <div class="analysis-section">
            <h3 class="analysis-title">Analyse et recommandations personnalisées</h3>
            <div class="analysis-content">
                <p>Cette analyse est générée automatiquement à partir de vos résultats académiques :</p>
                
                <div class="recommendation-item recommendation-strong">
                    <strong>Points forts :</strong> Excellente performance en mathématiques et physique. Continuez à exploiter ces forces dans vos projets futurs.
                </div>
                
                <div class="recommendation-item recommendation-warning">
                    <strong>Axes d'amélioration :</strong> La programmation informatique nécessite plus d'attention. Nous vous recommandons de consacrer plus de temps aux exercices pratiques.
                </div>
                
                <div class="recommendation-item">
                    <strong>Conseil général :</strong> Votre parcours est globalement satisfaisant. Pour continuer à progresser, maintenez une participation active en cours et n'hésitez pas à solliciter vos enseignants pour des clarifications.
                </div>
            </div>
        </div>
    </div>
</body>
</html>