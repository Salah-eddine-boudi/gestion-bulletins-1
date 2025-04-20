<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\UniteEnseignement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use DB;

class BulletinController extends Controller
{
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

        // Récupérer les UE avec leurs matières, évaluations et calcul du rang pour chaque matière
        $unitesEnseignement = $this->getUnitesAvecNotes($eleve->id_eleve);
        $ectsValides       = $this->calculerECTS($unitesEnseignement);
        $moyenneGenerale    = $this->calculerMoyenne($unitesEnseignement);
        $vhTotal            = $unitesEnseignement->sum('volumeHoraireTotal');
        $ectsTotal          = $unitesEnseignement->sum('creditsTotal');

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
        $minGeneral = $notesAll->count() ? $notesAll->min() : '';
        $maxGeneral = $notesAll->count() ? $notesAll->max() : '';
        $noteFinaleGenerale = $moyenneGenerale;

        // Calcul du rang global dans la classe
        $ranking = $this->getClasseRanking($eleve->niveau_scolaire);
        $rangGeneral = '';
        foreach ($ranking as $i => $item) {
            if ($item['eleve']->id_eleve == $eleve->id_eleve) {
                $rangGeneral = ($i + 1) . '/' . $ranking->count();
                break;
            }
        }

        return view('bulletins.show', compact(
            'eleve', 
            'unitesEnseignement', 
            'moyenneGenerale', 
            'ectsValides',
            'vhTotal',
            'ectsTotal',
            'minGeneral',
            'maxGeneral',
            'noteFinaleGenerale',
            'rangGeneral'
        ));
    }

    public function exportPdf($id): Response
    {
        $eleve = Eleve::with('user')->findOrFail($id);
        $this->verifierDroits($eleve);

        $unitesEnseignement = $this->getUnitesAvecNotes($id);
        $moyenneGenerale    = $this->calculerMoyenne($unitesEnseignement);
        $ectsValides        = $this->calculerECTS($unitesEnseignement);
        $vhTotal            = $unitesEnseignement->sum('volumeHoraireTotal');
        $ectsTotal          = $unitesEnseignement->sum('creditsTotal');

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
        $noteFinaleGenerale = is_numeric($moyenneGenerale) ? number_format($moyenneGenerale, 2) : $moyenneGenerale;

        // Calcul du rang global dans la classe
        $ranking = $this->getClasseRanking($eleve->niveau_scolaire);
        $rangGeneral = '';
        foreach ($ranking as $i => $item) {
            if ($item['eleve']->id_eleve == $eleve->id_eleve) {
                $rangGeneral = ($i + 1) . '/' . $ranking->count();
                break;
            }
        }

        $pdf = Pdf::loadView('bulletins.pdf', compact(
            'eleve',
            'unitesEnseignement',
            'moyenneGenerale',
            'ectsValides',
            'vhTotal',
            'ectsTotal',
            'minGeneral',
            'maxGeneral',
            'noteFinaleGenerale',
            'rangGeneral'
        ));
        return $pdf->download("bulletin_{$eleve->user->nom}_{$eleve->user->prenom}.pdf");
    }

    public function exportExcel($id)
    {
        $id = is_array($id) && isset($id['id']) ? $id['id'] : $id;
        $id = (int)$id;

        $eleve = Eleve::with('user')->findOrFail($id);
        $this->verifierDroits($eleve);

        $unites = $this->getUnitesAvecNotes($eleve->id_eleve);
        $exportData = collect();
        foreach ($unites as $unite) {
            foreach ($unite->matieres as $matiere) {
                $exportData->push([
                    'Unité d\'enseignement'   => $unite->intitule,
                    'Matière'                 => $matiere->intitule,
                    'Volume Horaire'          => $matiere->volume_horaire ?? '',
                    'Crédits ECTS'            => is_numeric($matiere->ects) ? $matiere->ects : '',
                    'Moyenne Pondérée'        => is_numeric($matiere->moyenne) ? number_format($matiere->moyenne, 2) : '',
                    'Note AV.R/20'            => is_numeric($matiere->moyenne_avant_ratt) ? number_format($matiere->moyenne_avant_ratt, 2) : '',
                    'Note A.R/20'             => is_numeric($matiere->note_ap_r) ? number_format($matiere->note_ap_r, 2) : '',
                    'Rang Matière'            => $matiere->rang ?? '',
                    'Crédits Validés Matière' => $matiere->credits_valides_matiere ?? ''
                ]);
            }
        }
        $filename = 'bulletin_' . Str::slug($eleve->user->nom . '_' . $eleve->user->prenom) . '.xlsx';

        return Excel::download(new class($exportData) implements 
            \Maatwebsite\Excel\Concerns\FromCollection, 
            \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;
                public function __construct($data)
                {
                    $this->data = $data;
                }
                public function collection()
                {
                    return $this->data;
                }
                public function headings(): array
                {
                    return [
                        'Unité d\'enseignement',
                        'Matière',
                        'Volume Horaire',
                        'Crédits ECTS',
                        'Moyenne Pondérée',
                        'Note AV.R/20',
                        'Note A.R/20',
                        'Rang Matière',
                        'Crédits Validés Matière'
                    ];
                }
            }, $filename);
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
        $eleve = Eleve::findOrFail($id);
        $niveau = $eleve->niveau_scolaire;
        $unites = UniteEnseignement::where('niveau_scolaire', $niveau)
            ->with(['matieres.evaluations' => function ($query) use ($id) {
                $query->where('id_eleve', $id);
            }])
            ->get();

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
                
                // La note finale vaut la note de rattrapage si elle existe, sinon la moyenne avant ratt
                $matiere->note_finale = is_numeric($noteRatt) ? $noteRatt : $matiere->moyenne_avant_ratt;
                
                // On peut aussi stocker la note AV.R/20 dans une propriété
                $matiere->note_av_r = $matiere->moyenne_avant_ratt;

                // Calcul de min et max pour la matière
                $matiere->min = $this->calculerMin($ds1, $ds2, $exam, $noteRatt);
                $matiere->max = $this->calculerMax($ds1, $ds2, $exam, $noteRatt);
                
                // Calcul du rang pour la matière
                $idMatiere = $matiere->id; // ou utilisez $matiere->id_matiere si nécessaire
                if ($idMatiere) {
                    $matiere->rang = $this->computeMatiereRank($idMatiere, $matiere->note_finale, $niveau);
                } else {
                    $matiere->rang = '';
                }

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
                $unite->moyenne = round($notesFinales->avg(), 2);
            } else {
                $unite->min = '';
                $unite->max = '';
                $unite->moyenne = '';
            }
            $unite->noteFinale = $unite->moyenne;
            $unite->rang = ''; // Rang global UE si besoin
        }
        return $unites;
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
        return $totalCoef > 0 ? round($total / $totalCoef, 2) : 'N/A';
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
     * Calcule le rang pour une matière donnée.
     *
     * Pour la matière $matiereId, récupère toutes les notes finales des élèves du niveau $niveau,
     * trie les notes en ordre décroissant, puis détermine la position de $currentNote.
     *
     * @param int $matiereId
     * @param mixed $currentNote
     * @param string $niveau
     * @return string Rang sous forme "X/Y" ou chaîne vide
     */
    protected function computeMatiereRank($matiereId, $currentNote, $niveau)
    {
        if (!$matiereId) {
            return '';
        }
        // Utilisez le nom exact de la table d'évaluation (ici "evaluation")
        $notes = DB::table('evaluation')
            ->join('eleves', 'evaluation.id_eleve', '=', 'eleves.id_eleve')
            ->where('evaluation.id_matiere', $matiereId)
            ->where('eleves.niveau_scolaire', $niveau)
            ->pluck('evaluation.note')
            ->filter(fn($v) => is_numeric($v))
            ->sortDesc()
            ->values();

        if ($notes->count() == 0) {
            return '';
        }
        $rankIndex = $notes->search(fn($value) => $value == $currentNote);
        if ($rankIndex === false) {
            return '';
        }
        return ($rankIndex + 1) . '/' . $notes->count();
    }

    /**
     * Calcule le classement global des élèves d'un niveau.
     *
     * Retourne une collection triée (descendant) avec chaque élément : ['eleve' => $eleve, 'average' => moyenne générale]
     */
    protected function getClasseRanking($niveau)
    {
        $eleves = Eleve::where('niveau_scolaire', $niveau)->get();
        $data = collect();
        foreach ($eleves as $e) {
            $unites = $this->getUnitesAvecNotes($e->id_eleve);
            $avg = $this->calculerMoyenne($unites);
            $data->push([
                'eleve'   => $e,
                'average' => is_numeric($avg) ? $avg : 0,
            ]);
        }
        return $data->sortByDesc('average')->values();
    }
}