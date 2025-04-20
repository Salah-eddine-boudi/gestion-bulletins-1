{{-- resources/views/bulletins/excel.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin Excel - {{ optional($eleve->user)->nom }} {{ optional($eleve->user)->prenom }}</title>
    <style>
        /* Basique pour Excel : évitez trop de styles complexes */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        table {
            width: 100%;
            border: 1px solid #333;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            vertical-align: middle;
        }
        th {
            background-color: #f0f0f0;
        }

        /* Alternance de couleur (row striping) */
        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .title {
            font-size: 1.2em;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        .fw-bold {
            font-weight: bold;
        }
        .text-muted {
            color: #666;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

    <h1>Bulletin Excel</h1>

    <!-- Informations de l'élève -->
    <table>
        <tr>
            <th class="fw-bold" style="width: 30%;">Élève</th>
            <td>
                {{ optional($eleve->user)->nom ?? 'N/A' }} 
                {{ optional($eleve->user)->prenom ?? '' }}
            </td>
        </tr>
        <tr>
            <th class="fw-bold">Niveau</th>
            <td>{{ $eleve->niveau_scolaire ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th class="fw-bold">ECTS Validés</th>
            <td>{{ $ectsValidés ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th class="fw-bold">Moyenne Générale</th>
            <td>
                @if(is_numeric($moyenneGenerale))
                    {{ number_format($moyenneGenerale, 2) }}
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>

    <!-- Contenu des Unités d'Enseignement -->
    @if(!empty($unitesEnseignement) && count($unitesEnseignement) > 0)
        @foreach($unitesEnseignement as $unite)
            <div class="title">
                {{ $unite->intitule ?? 'Unité Sans Intitulé' }}
            </div>
            @php
                // On s’assure d’itérer sur la collection matieres si elle existe
                $matieres = $unite->matieres ?? collect();
            @endphp

            @if($matieres->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th>Volume Horaire</th>
                            <th>Crédits ECTS</th>
                            <th>Moyenne Pondérée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matieres as $matiere)
                            <tr>
                                <td>{{ $matiere->intitule ?? 'N/A' }}</td>
                                <td>{{ $matiere->volume_horaire ?? 'N/A' }}</td>
                                <td>
                                    @if(is_numeric($matiere->ects))
                                        {{ $matiere->ects }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @if(is_numeric($matiere->moyenne))
                                        {{ number_format($matiere->moyenne, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-muted mb-2">
                    <em>Aucune matière</em>
                </div>
            @endif
        @endforeach
    @else
        <p class="text-muted"><em>Aucune unité d'enseignement à afficher.</em></p>
    @endif

</body>
</html>
