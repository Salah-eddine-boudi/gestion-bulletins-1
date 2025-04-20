@extends('layouts.app')

@section('content')
<div class="container py-4 bulletin-container">
    <!-- En-tête avec logo Junia et informations principales -->
    <div class="row bulletin-header mb-4">
        <div class="col-md-4">
            <div class="logo-container d-flex align-items-center">
                <h1 class="text-primary fw-bold mb-0">JUNIA</h1>
                <div class="ms-2 small text-secondary">
                    <p class="mb-0">Grande</p>
                    <p class="mb-0">école</p>
                    <p class="mb-0">d'ingénieurs</p>
                </div>
            </div>
            <p class="text-secondary small">HEI-ISEN-ISA</p>
        </div>
        <div class="col-md-4 text-center">
            <h2 class="bulletin-title">Bulletin de Notes</h2>
            <p class="text-muted">Année Académique 2022-2023</p>
        </div>
        <div class="col-md-4">
            <div class="student-info-card">
                <h4 class="fw-bold mb-2">{{ $eleve->user->nom }} {{ $eleve->user->prenom }}</h4>
                <p class="mb-1"><i class="bi bi-calendar-date me-1"></i> Né(e) le {{ \Carbon\Carbon::parse($eleve->user->date_naissance)->format('d/m/Y') }}</p>
                <p class="mb-1"><i class="bi bi-mortarboard me-1"></i> Classe : {{ $eleve->niveau_scolaire }}</p>
                <p class="mb-1"><i class="bi bi-award me-1"></i> ECTS Validés : <span class="fw-bold badge bg-success">{{ $ectsValides ?? '60' }}</span></p>
            </div>
        </div>
    </div>

    <!-- Résumé des résultats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div class="stats-info">
                    <h6>Moyenne Générale</h6>
                    <h3>{{ $noteFinaleGenerale ?? '13,63' }}/20</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="stats-info">
                    <h6>Classement</h6>
                    <h3>{{ $rangGeneral ?? '6/22' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-info">
                    <h6>Volume Horaire</h6>
                    <h3>{{ $vhTotal ?? '720' }}h</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <div class="stats-info">
                    <h6>Crédits ECTS</h6>
                    <h3>{{ $ectsValides ?? '60' }}/{{ $ectsTotal ?? '60' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des résultats amélioré -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Résultats académiques</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0 result-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%">Libellé</th>
                            <th>V.H</th>
                            <th>Crédits</th>
                            <th>Min.</th>
                            <th>Max.</th>
                            <th>Moy.</th>
                            <th>Note AV.R/20</th>
                            <th>Note A.R/20</th>
                            <th>Note Finale</th>
                            <th>Rang</th>
                            <th>Crédits Validés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ligne globale pour l'année -->
                        <tr class="global-row fw-bold table-primary">
                            <td>
                                @if($eleve->niveau_scolaire == 'JM1')
                                    1ère année JUNIA MAROC
                                @elseif($eleve->niveau_scolaire == 'JM2')
                                    2ème année JUNIA MAROC
                                @else
                                    3ème année JUNIA MAROC
                                @endif
                            </td>
                            <td>{{ $vhTotal ?? '720' }}</td>
                            <td>{{ $ectsTotal ?? '60' }}</td>
                            <td>{{ $minGeneral ?? '9,73' }}</td>
                            <td>{{ $maxGeneral ?? '17,66' }}</td>
                            <td>{{ $moyenneGenerale ?? '12,62' }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ $noteFinaleGenerale ?? '13,63' }}</td>
                            <td>{{ $rangGeneral ?? '6/22' }}</td>
                            <td>{{ $ectsValides ?? '60' }}</td>
                        </tr>

                        <!-- Pour chaque Unité d'Enseignement -->
                        @foreach($unitesEnseignement ?? [] as $unite)
                            @php
                                $uniteVH            = $unite->volumeHoraireTotal ?? '';
                                $uniteCredits       = $unite->creditsTotal ?? '';
                                $uniteMin           = $unite->min ?? '';
                                $uniteMax           = $unite->max ?? '';
                                $uniteMoy           = $unite->moyenne ?? '';
                                $uniteNoteFinale    = $unite->noteFinale ?? '';
                                $uniteRang          = $unite->rang ?? '';
                                $uniteCreditsValides= $unite->creditsValides ?? '';
                            @endphp
                            <tr class="ue-row table-secondary fw-bold">
                                <td>Unité d'enseignement - {{ $unite->intitule ?? 'Sciences fondamentales' }}</td>
                                <td>{{ $uniteVH }}</td>
                                <td>{{ $uniteCredits }}</td>
                                <td>{{ $uniteMin }}</td>
                                <td>{{ $uniteMax }}</td>
                                <td>{{ $uniteMoy }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $uniteNoteFinale }}</td>
                                <td>{{ $uniteRang }}</td>
                                <td>{{ $uniteCreditsValides }}</td>
                            </tr>

                            <!-- Pour chaque Matière de l'UE -->
                            @foreach($unite->matieres ?? [] as $matiere)
                                @php
                                    $vhMatiere       = $matiere->volume_horaire ?? '';
                                    $creditsMatiere  = $matiere->ects ?? '';
                                    $minMatiere      = $matiere->min ?? '';
                                    $maxMatiere      = $matiere->max ?? '';
                                    $moyMatiere      = $matiere->moyenne ?? '';
                                    $noteAvR         = $matiere->note_av_r ?? '';
                                    $noteApR         = $matiere->note_ap_r ?? '';
                                    $noteFinale      = $matiere->note_finale ?? '';
                                    $rangMatiere     = $matiere->rang ?? '';
                                    $creditsValidMatiere = $matiere->credits_valides_matiere ?? '';
                                @endphp
                                <tr class="matiere-row">
                                    <td class="ps-4">{{ $matiere->intitule ?? 'Mathématiques' }}</td>
                                    <td class="text-center">{{ $vhMatiere }}</td>
                                    <td class="text-center">{{ $creditsMatiere }}</td>
                                    <td class="text-center">{{ $minMatiere }}</td>
                                    <td class="text-center">{{ $maxMatiere }}</td>
                                    <td class="text-center">
                                        {{ is_numeric($moyMatiere) ? number_format($moyMatiere, 2) : $moyMatiere }}
                                    </td>
                                    <td class="text-center">
                                        {{ is_numeric($noteAvR) ? number_format($noteAvR, 2) : $noteAvR }}
                                    </td>
                                    <td class="text-center">
                                        {{ is_numeric($noteApR) ? number_format($noteApR, 2) : $noteApR }}
                                    </td>
                                    <td class="text-center fw-bold 
                                        {{ isset($noteFinale) && $noteFinale >= 14 ? 'text-success' : '' }}
                                        {{ isset($noteFinale) && $noteFinale < 10 ? 'text-danger' : '' }}">
                                        {{ is_numeric($noteFinale) ? number_format($noteFinale, 2) : $noteFinale }}
                                    </td>
                                    <td class="text-center">{{ $rangMatiere }}</td>
                                    <td class="text-center">{{ $creditsValidMatiere }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Décision et signature -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Appréciation et décision</h5>
                </div>
                <div class="card-body">
                    <div class="decision-content">
                        <div class="decision-icon mb-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <h4 class="decision-title mb-3">Passage en 
                            @if($eleve->niveau_scolaire == 'JM1')
                                2ème année (JM2)
                            @elseif($eleve->niveau_scolaire == 'JM2')
                                3ème année (JM3)
                            @else
                                Cycle ingénieur
                            @endif
                        </h4>
                        <p class="decision-text">
                            L'étudiant a satisfait aux exigences académiques de l'année en cours et est autorisé à passer à l'année supérieure.
                        </p>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Validation</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-3">Édité le {{ now()->format('d/m/Y') }}</p>
                    <div class="signature-box mb-3">
                        @if(file_exists(public_path('storage/image/signature.png')))
                            <img src="{{ asset('storage/image/signature.png') }}" alt="Signature" class="img-fluid">
                        @else
                            <img src="{{ asset('images/tampon.png') }}" alt="Tampon et signature" class="img-fluid">
                        @endif
                    </div>
                    <p class="mb-0">Direction des Études<br>JUNIA Maroc</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="row">
        <div class="col-12 text-center">
            <a href="{{ route('bulletins.export.pdf', ['id' => $eleve->id_eleve ?? 1]) }}" class="btn btn-primary me-2">
                <i class="bi bi-file-pdf me-1"></i> Télécharger PDF
            </a>
            <a href="{{ route('bulletins.export.excel', $eleve->id_eleve ?? 1) }}" class="btn btn-success me-2">
                <i class="bi bi-file-excel me-1"></i> Télécharger Excel
            </a>
            <a href="#" class="btn btn-info" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimer
            </a>
        </div>
    </div>

    <!-- Pied de page graphique -->
    <div class="mt-5 bulletin-footer">
        <div class="row g-0">
            <div class="col-12">
                <div class="color-bar"></div>
                <p class="text-center text-muted small mt-2">
                    JUNIA Maroc • Campus Rabat • Année Académique 2022-2023
                </p>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4B0082;
    --secondary-color: #FFA500;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --info-color: #17a2b8;
    --light-bg: #f8f9fa;
    --border-color: #dee2e6;
}

body {
    background-color: var(--light-bg);
    font-family: 'Poppins', sans-serif;
}

/* Conteneur principal */
.bulletin-container {
    max-width: 1140px;
    margin: 0 auto;
    background-color: #fff;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
    padding: 2rem;
}

/* En-tête du bulletin */
.bulletin-header {
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.logo-container {
    display: flex;
    align-items: center;
}

.text-primary {
    color: var(--primary-color) !important;
}

.text-secondary {
    color: #6c757d !important;
}

.bulletin-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

/* Carte d'informations étudiant */
.student-info-card {
    background-color: var(--light-bg);
    border-left: 4px solid var(--primary-color);
    padding: 1rem;
    border-radius: 0.25rem;
}

/* Cartes statistiques */
.stats-card {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    display: flex;
    align-items: center;
    height: 100px;
    border-left: 4px solid var(--primary-color);
}

.stats-icon {
    font-size: 2rem;
    color: var(--primary-color);
    margin-right: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
}

.stats-info h6 {
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.stats-info h3 {
    color: var(--primary-color);
    margin-bottom: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

/* Tableau des résultats */
.result-table th {
    font-weight: 600;
    color: #495057;
    vertical-align: middle;
    border: 1px solid var(--border-color);
}

.result-table td {
    border: 1px solid var(--border-color);
    vertical-align: middle;
}

.bg-primary {
    background-color: var(--primary-color) !important;
}

.table-primary {
    background-color: rgba(75, 0, 130, 0.1);
}

.table-secondary {
    background-color: #f1f1f1;
}

.global-row, .ue-row {
    font-weight: 600;
}

/* Carte de décision */
.decision-content {
    text-align: center;
    padding: 1.5rem;
}

.decision-icon {
    font-size: 3rem;
    color: var(--success-color);
}

.decision-title {
    color: var(--primary-color);
    font-weight: 700;
}

.decision-text {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Boîte de signature */
.signature-box {
    border: 1px dashed var(--border-color);
    padding: 1rem;
    margin-bottom: 1rem;
}

.signature-box img {
    max-height: 80px;
}

/* Pied de page */
.bulletin-footer {
    margin-top: 3rem;
}

.color-bar {
    height: 6px;
    background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    border-radius: 3px;
}

/* Mise en forme pour l'impression */
@media print {
    body {
        background-color: #fff;
    }
    
    .bulletin-container {
        box-shadow: none;
        padding: 0;
    }
    
    .btn {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #f1f1f1 !important;
        color: #333 !important;
    }
}

/* Styles pour les badges et les boutons */
.badge {
    padding: 0.4rem 0.6rem;
    font-weight: 600;
    border-radius: 50rem;
}

.bg-success {
    background-color: var(--success-color) !important;
}

.text-success {
    color: var(--success-color) !important;
}

.text-danger {
    color: var(--danger-color) !important;
}

.btn {
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    border-radius: 0.25rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: #3a006d;
    border-color: #3a006d;
}

.btn-success {
    background-color: var(--success-color);
    border-color: var(--success-color);
}

.btn-info {
    background-color: var(--info-color);
    border-color: var(--info-color);
}
</style>
@endsection