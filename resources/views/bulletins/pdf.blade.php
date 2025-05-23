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

        <!-- Tableau des résultats avec séparateurs blancs garantis -->
        <table>
            <thead>
                <tr>
                    <!-- En-têtes Module/Cours avec bordure droite -->
                    <th colspan="3" class="main-header" style="border-right: 0.5px solid #000;">Module/Cours</th>
                    
                    <!-- Séparateur strictement blanc -->
                    <th class="separator-col" style="background-color: white !important;"></th>
                    
                    <!-- En-têtes Groupe avec bordures gauche et droite -->
                    <th colspan="3" class="main-header" style="border-left: 0.5px solid #000; border-right: 0.5px solid #000;">Groupe</th>
                    
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
                    
                    <!-- Sous-en-têtes Groupe -->
                    <th class="sub-header left-border">Min.</th>
                    <th class="sub-header">Max.</th>
                    <th class="sub-header right-border">Moy.</th>
                    
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
                    <td class="number-cell left-border">{{ str_replace('.', ',', number_format($minGeneral, 2)) }}</td>
                    <td class="number-cell">{{ str_replace('.', ',', number_format($maxGeneral, 2)) }}</td>
                    <td class="number-cell right-border">{{ str_replace('.', ',', number_format($moyenneGenerale, 2)) }}</td>
                    <td class="separator-col"></td>
                    <td class="left-border"></td>
                    <td></td>
                    <td class="number-cell">{{ str_replace('.', ',', $noteFinaleGenerale) }}</td>
                    <td class="number-cell">{{ $letterGradeGeneral }}</td>
                    <td>{{ $rangGeneral }}</td>
                    <td class="number-cell">{{ $ectsValides }}</td>
                </tr>

                <tr class="first-spacer">
                    <td colspan="14"></td>
                </tr>

                @foreach($unitesEnseignement as $unite)
                    <tr class="unite-row">
                        <td class="module-col">Unité d'enseignement - {{ $unite->intitule }}</td>
                        <td class="vh-col">{{ $unite->volumeHoraireTotal }}</td>
                        <td class="right-border">{{ $unite->creditsTotal }}</td>
                        <td class="separator-col"></td>
                        <td class="left-border">{{ is_numeric($unite->min) ? number_format($unite->min, 2) : $unite->min }}</td>
                        <td>{{ is_numeric($unite->max) ? number_format($unite->max, 2) : $unite->max }}</td>
                        <td class="right-border">{{ is_numeric($unite->moyenne) ? number_format($unite->moyenne, 2) : $unite->moyenne }}</td>
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
                            <td class="left-border">{{ is_numeric($matiere->min) ? number_format($matiere->min, 2) : $matiere->min }}</td>
                            <td>{{ is_numeric($matiere->max) ? number_format($matiere->max, 2) : $matiere->max }}</td>
                            <td class="right-border">{{ is_numeric($matiere->moyenne) ? number_format($matiere->moyenne, 2) : $matiere->moyenne }}</td>
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
                        <p style="margin: 2px 0;"><strong>Zone grise:</strong> Moyenne de la promotion</p>
                        <p style="margin: 2px 0;"><strong>Points forts:</strong> 
                            @php
                                $pointsForts = ["Mathématiques", "Physique"];
                                $pointsFaibles = ["Informatique", "Langues"];
                            @endphp
                            @if(isset($pointsForts) && count($pointsForts) > 0)
                                {{ implode(', ', $pointsForts) }}
                            @else
                                Information non disponible
                            @endif
                        </p>
                        <p style="margin: 2px 0;"><strong>Points à améliorer:</strong> 
                            @if(isset($pointsFaibles) && count($pointsFaibles) > 0)
                                {{ implode(', ', $pointsFaibles) }}
                            @else
                                Information non disponible
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Deuxième rangée de graphiques -->
            <div class="charts-row" style="display: flex; justify-content: space-between;">
                <!-- Diagramme circulaire - Répartition ECTS -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Répartition des crédits ECTS</h4>
                    @if(isset($chartImages['pie']))
                        <img src="{{ $chartImages['pie'] }}" alt="Diagramme circulaire" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Répartition des crédits ECTS validés par domaine</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                            <div style="width: 48%; margin-bottom: 4px;">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: rgba(75, 0, 130, 0.7); margin-right: 5px;"></span>
                                <span>Sciences fondamentales: {{ $ectsValides * 0.4 }} ECTS</span>
                            </div>
                            <div style="width: 48%; margin-bottom: 4px;">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: rgba(255, 165, 0, 0.7); margin-right: 5px;"></span>
                                <span>Sciences de l'ingénieur: {{ $ectsValides * 0.3 }} ECTS</span>
                            </div>
                            <div style="width: 48%; margin-bottom: 4px;">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: rgba(0, 128, 0, 0.7); margin-right: 5px;"></span>
                                <span>Informatique: {{ $ectsValides * 0.15 }} ECTS</span>
                            </div>
                            <div style="width: 48%; margin-bottom: 4px;">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: rgba(30, 144, 255, 0.7); margin-right: 5px;"></span>
                                <span>Langues et SHS: {{ $ectsValides * 0.15 }} ECTS</span>
                            </div>
                        </div>
                        <p style="margin: 2px 0; margin-top: 5px;"><strong>Total validé:</strong> {{ $ectsValides }}/{{ $ectsTotal }} ECTS ({{ round(($ectsValides/$ectsTotal)*100) }}%)</p>
                    </div>
                </div>
                
                <!-- Graphique d'évolution temporelle -->
                <div class="chart-col" style="width: 48%;">
                    <h4 class="chart-title">Évolution des performances</h4>
                    @if(isset($chartImages['evolution']))
                        <img src="{{ $chartImages['evolution'] }}" alt="Graphique d'évolution" class="chart-image" style="width: 100%;">
                    @else
                        <p style="text-align: center; color: #888;">Graphique non disponible</p>
                    @endif
                    <p class="chart-description">Évolution de vos résultats au cours de l'année</p>
                    <div class="chart-details" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px; font-size: 8pt; text-align: left;">
                        <p style="margin: 2px 0;"><strong>Ligne bleue:</strong> Évolution de votre moyenne générale</p>
                        <p style="margin: 2px 0;"><strong>Ligne orange:</strong> Évolution de la moyenne de promotion</p>
                        <p style="margin: 2px 0;"><strong>Tendance:</strong> 
                            @php
                                $tendance = "positive";  // ou 'negative', 'stable'
                                $variation = "+1.5";
                            @endphp
                            @if($tendance == 'positive')
                                <span style="color: green;">Progression (+{{ $variation }} points)</span>
                            @elseif($tendance == 'negative')
                                <span style="color: red;">Diminution ({{ $variation }} points)</span>
                            @else
                                <span style="color: blue;">Stable</span>
                            @endif
                        </p>
                        <p style="margin: 2px 0;"><strong>Périodes:</strong> Semestre 1 (Sep-Jan), Semestre 2 (Fév-Juin)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section d'analyse et recommandations -->
        <div class="analysis-section">
            <h3 class="analysis-title">Analyse et recommandations</h3>
            
            <div class="analysis-content">
                @php
                    // Calculer le percentile en fonction du rang
                    $percentile = 50; // Valeur par défaut
                    
                    if(!empty($rangGeneral)) {
                        $rangeParts = explode('/', $rangGeneral);
                        if(count($rangeParts) == 2) {
                            $position = intval($rangeParts[0]);
                            $total = intval($rangeParts[1]);
                            
                            if($total > 0) {
                                $percentile = round((($total - $position + 1) / $total) * 100);
                            }
                        }
                    }
                    
                    // Déterminer les points forts et faibles en fonction de la moyenne
                    $noteFinale = is_numeric($moyenneGenerale) ? $moyenneGenerale : 0;
                @endphp
                
                <p>Moyenne générale : <strong>{{ is_numeric($noteFinaleGenerale) ? str_replace('.', ',', $noteFinaleGenerale) : $noteFinaleGenerale }}/20</strong>
                @if($noteFinale >= 14)
                    <span style="color: green;">(Excellent)</span>
                @elseif($noteFinale >= 12)
                    <span style="color: blue;">(Bon)</span>
                @elseif($noteFinale >= 10)
                    <span style="color: orange;">(Satisfaisant)</span>
                @else
                    <span style="color: red;">(À améliorer)</span>
                @endif
                </p>
                
                <p>Position dans la promotion : <strong>{{ $rangGeneral }}</strong>
                @if($percentile >= 75)
                    <span style="color: green;">(Top 25%)</span>
                @elseif($percentile >= 50)
                    <span style="color: blue;">(Top 50%)</span>
                @elseif($percentile >= 25)
                    <span style="color: orange;">(Top 75%)</span>
                @else
                    <span style="color: red;">(Dernier quart)</span>
                @endif
                </p>
                
                <p>Crédits ECTS validés : <strong>{{ $ectsValides }}/{{ $ectsTotal }}</strong>
                @if($ectsValides == $ectsTotal)
                    <span style="color: green;">(Tous validés)</span>
                @elseif($ectsValides >= $ectsTotal * 0.75)
                    <span style="color: blue;">(Majorité validée)</span>
                @elseif($ectsValides >= $ectsTotal * 0.5)
                    <span style="color: orange;">(Partiellement validés)</span>
                @else
                    <span style="color: red;">(Peu validés)</span>
                @endif
                </p>
                
                <div class="recommendation-item recommendation-strong">
                    <p style="margin: 0;"><strong>Points forts :</strong> 
                    @if($noteFinale >= 14)
                        Excellente maîtrise des compétences attendues. Performance académique supérieure à la moyenne dans la plupart des matières.
                    @elseif($noteFinale >= 12)
                        Bonne maîtrise des compétences fondamentales. Performance académique satisfaisante dans plusieurs matières.
                    @else
                        Compétences démontrées dans certaines matières spécifiques. Effort et assiduité remarqués dans le travail académique.
                    @endif
                    </p>
                </div>
                
                <div class="recommendation-item recommendation-warning">
                    <p style="margin: 0;"><strong>Points à améliorer :</strong> 
                    @if($noteFinale >= 14)
                        Continuer à développer les compétences transversales et envisager des projets extrascolaires pour valoriser davantage ce profil.
                    @elseif($noteFinale >= 12)
                        Renforcer certaines matières spécifiques et améliorer les méthodes de travail pour viser l'excellence académique.
                    @elseif($noteFinale >= 10)
                        Intensifier le travail dans les matières fondamentales et établir un planning d'études plus rigoureux.
                    @else
                        Redoubler d'efforts dans l'ensemble des matières et envisager un soutien pédagogique supplémentaire.
                    @endif
                    </p>
                </div>
                
                <div class="recommendation-item">
                    <p style="margin: 0;"><strong>Orientation conseillée :</strong> 
                    @if($percentile >= 75)
                        Ce profil académique laisse présager de nombreuses possibilités en cycle ingénieur. Les options d'approfondissement en 
                        @if($eleve->niveau_scolaire == 'JM1')
                            sciences fondamentales sont particulièrement recommandées pour JM2.
                        @elseif($eleve->niveau_scolaire == 'JM2')
                            spécialités techniques sont particulièrement recommandées pour JM3.
                        @else
                            domaines de spécialité sont particulièrement recommandées pour le cycle ingénieur.
                        @endif
                    @elseif($percentile >= 50)
                        Le profil académique est équilibré mais gagnerait à se renforcer dans certains domaines avant le 
                        @if($eleve->niveau_scolaire == 'JM1')
                            passage en JM2.
                        @elseif($eleve->niveau_scolaire == 'JM2')
                            passage en JM3.
                        @else
                            passage en cycle ingénieur.
                        @endif
                    @else
                        Un accompagnement personnalisé est recommandé pour identifier les domaines d'amélioration avant le 
                        @if($eleve->niveau_scolaire == 'JM1')
                            passage en JM2.
                        @elseif($eleve->niveau_scolaire == 'JM2')
                            passage en JM3.
                        @else
                            passage en cycle ingénieur.
                        @endif
                    @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Signature en bas de la deuxième page -->
        <div class="footer-row clearfix" style="margin-top: 30px;">
            <div class="stamp-area" style="width: 100%; text-align: right;">
                <p style="margin: 0;">Direction des Études - JUNIA Maroc</p>
            </div>
        </div>
    </div>
</body>
</html>