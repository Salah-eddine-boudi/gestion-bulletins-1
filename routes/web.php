<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ElevesController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UniteEnseignementController;
use App\Http\Controllers\AssurerController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\DirecteurPedagogiqueController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EvaluationParamController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard (auth et vérification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Routes d'authentification Breeze
require __DIR__.'/auth.php';

// Gestion du profil utilisateur (auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ressources publiques
Route::resource('unites-enseignement', UniteEnseignementController::class);
Route::resource('matieres', MatiereController::class);
Route::resource('professeurs', ProfesseurController::class);
Route::resource('admins', AdminController::class);  // Si besoin, ne pas dupliquer
Route::resource('assurer', AssurerController::class);

// Ressources Evaluation et Inscription

Route::resource('inscriptions', InscriptionController::class)->except(['show', 'edit', 'update', 'destroy']);

// Routes personnalisées pour inscriptions (clé composite)
Route::get('inscriptions/{id_eleve}/{annee_universitaire}', [InscriptionController::class, 'show'])->name('inscriptions.show');
Route::get('inscriptions/{id_eleve}/{annee_universitaire}/edit', [InscriptionController::class, 'edit'])->name('inscriptions.edit');
Route::put('inscriptions/{id_eleve}/{annee_universitaire}', [InscriptionController::class, 'update'])->name('inscriptions.update');
Route::delete('inscriptions/{id_eleve}/{annee_universitaire}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');

// Directeur pédagogique
Route::resource('directeurs', DirecteurPedagogiqueController::class);
Route::get('directeurs/{directeur}/edit', [DirecteurPedagogiqueController::class, 'edit'])->name('directeurs.edit');

// Saisie des notes pour les groupes de langue
Route::get('/saisie-notes-groupe', [EvaluationController::class, 'createGroupLangue'])->name('saisie-notes-groupe');

// Routes pour les élèves
Route::resource('eleves', ElevesController::class)->parameters([
    'eleves' => 'id_eleve'
]);

// Route Ajax pour récupérer les élèves filtrés (pour évaluations)
Route::get('/get-eleves', [EvaluationController::class, 'getElevesByFilters'])->name('evaluations.getEleves');

// Bulletin
Route::get('/bulletins', [BulletinController::class, 'index'])->name('bulletins.index');
Route::get('/bulletins/{id}', [BulletinController::class, 'show'])->name('bulletins.show');
Route::get('/bulletins/{id}/pdf', [BulletinController::class, 'exportPdf'])->name('bulletins.export.pdf');
Route::get('/bulletins/{id}/excel', [BulletinController::class, 'exportExcel'])->name('bulletins.export.excel');

// Classes
Route::resource('classes', ClasseController::class);

// -------------------------------
// Routes pour le RATTRAPAGE
// -------------------------------
// 2) Route dynamique pour l'affichage d'une évaluation (doit être en dernier)

// Routes spécifiques pour les évaluations de rattrapage
Route::get('/evaluations/rattrapage', [EvaluationController::class, 'rattrapageView'])->name('evaluations.rattrapage');
Route::get('/evaluations/create-rattrapage', [EvaluationController::class, 'createRatt'])->name('evaluations.createRatt');
Route::get('/evaluations/eleves-rattrapage', [EvaluationController::class, 'getElevesPourRattrapage'])->name('evaluations.getRattrapage');
Route::post('/evaluations/store-rattrapage', [EvaluationController::class, 'storeRattGroup'])->name('evaluations.storeRattrapage');

// La resource pour les autres actions doit être déclarée après
Route::resource('evaluations', EvaluationController::class);



Route::middleware(['auth'])->group(function(){
    Route::resource('evaluation-params', EvaluationParamController::class)->only([
        'index', 'create', 'store','edit'
    ]);
});

Route::middleware(['auth'])->group(function(){
    Route::resource('evaluation-params', EvaluationParamController::class);
});
