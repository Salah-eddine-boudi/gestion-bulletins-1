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
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête avec informations étudiant -->
        <div class="header">
            <h3 style="margin-bottom: 3px; font-size: 11pt;">Monsieur / Madame {{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h3>
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
                    <p style="margin: 2px 0;"><strong>Nombre d'ECTS Validés : {{ $ectsValides }} sur 60</strong></p>
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
                    <th colspan="5" class="main-header" style="border-left: 0.5px solid #000;">Étudiant</th>
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
                    <td>{{ $rangGeneral }}</td>
                    <td class="number-cell">{{ $ectsValides }}</td>
                </tr>

                <tr class="first-spacer">
                    <td colspan="13"></td>
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
</body>
</html>