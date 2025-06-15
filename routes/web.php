<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MatiereController,
    ProfileController,
    ElevesController,
    ProfesseurController,
    AdminController,
    UniteEnseignementController,
    AssurerController,
    EvaluationController,
    EvaluationParamController,
    InscriptionController,
    DirecteurPedagogiqueController,
    BulletinController,
    ClasseController
};





/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/contact', fn () => view('contact'))->name('contact');

/*
|--------------------------------------------------------------------------
| Auth + dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Routes protégées (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ---------- Profil utilisateur ---------- */
    Route::get   ('/profile', [ProfileController::class, 'edit' ])->name('profile.edit');
    Route::patch ('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---------- Ressources « simples » ---------- */
    Route::resources([
        'unites-enseignement' => UniteEnseignementController::class,
        'matieres'            => MatiereController::class,
        'professeurs'         => ProfesseurController::class,
        'admins'              => AdminController::class,
        'assurer'             => AssurerController::class,
        'classes'             => ClasseController::class,
        'directeurs'          => DirecteurPedagogiqueController::class,
        'eleves'              => ElevesController::class,
    ], [
        // Paramètre personnalisé pour élèves
        'eleves' => ['parameters' => ['eleves' => 'id_eleve']],
    ]);

    /* ---------- Inscriptions (clé composite) ---------- */
    Route::resource('inscriptions', InscriptionController::class)
        ->except(['show', 'edit', 'update', 'destroy']);

    Route::prefix('inscriptions')->name('inscriptions.')->group(function () {
        Route::get   ('{id_eleve}/{annee_universitaire}',        [InscriptionController::class, 'show'   ])->name('show');
        Route::get   ('{id_eleve}/{annee_universitaire}/edit',   [InscriptionController::class, 'edit'   ])->name('edit');
        Route::put   ('{id_eleve}/{annee_universitaire}',        [InscriptionController::class, 'update' ])->name('update');
        Route::delete('{id_eleve}/{annee_universitaire}',        [InscriptionController::class, 'destroy'])->name('destroy');
    });

    /* ---------- Bulletins ---------- */
    Route::prefix('bulletins')->name('bulletins.')->group(function () {
        Route::get('/',            [BulletinController::class, 'index'     ])->name('index');
        Route::get('{id}',         [BulletinController::class, 'show'      ])->name('show');
        Route::get('{id}/pdf',     [BulletinController::class, 'exportPdf' ])->name('export.pdf');
        Route::get('{id}/excel',   [BulletinController::class, 'exportExcel'])->name('export.excel');
    });

    /* ---------- Routes spécifiques Évaluations (rattrapage) ---------- */
    Route::prefix('evaluations')->name('evaluations.')->group(function () {
        Route::get ('/rattrapage',                [EvaluationController::class, 'rattrapageView'    ])->name('rattrapage');
        Route::get ('/create-rattrapage',         [EvaluationController::class, 'createRatt'        ])->name('createRatt');
        Route::get ('/eleves-rattrapage',         [EvaluationController::class, 'getElevesPourRattrapage'])->name('getRattrapage');
        Route::post('/store-rattrapage',          [EvaluationController::class, 'storeRattGroup'    ])->name('storeRattrapage');

        // Ajax helper
        Route::get ('/get-eleves',                [EvaluationController::class, 'getElevesByFilters'])->name('getEleves');
    });

    /* ---------- Ressource Évaluations (après les routes « spéciales ») ---------- */
    Route::resource('evaluations', EvaluationController::class);

    /* ---------- Paramétrages d’évaluations ---------- */
    Route::resource('evaluation-params', EvaluationParamController::class);
});
/* routes/web.php */

// zone publique
Route::view('/',      'public.accueil')->name('home');

// zone protégée admin
Route::middleware(['auth','can:admin'])->group(function () {
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
});




use Illuminate\Http\Request;


Route::middleware('auth')->get('/mes-cookies', function (Request $request) {
    // Tous les cookies
    $cookies = $request->cookies->all();

    // Toutes les variables de session
    $session = $request->session()->all();

    // L'utilisateur authentifié
    $user = $request->user(); // ou Auth::user()

    // Passez tout ça à une vue
    return view('mes-cookies', compact('cookies', 'session', 'user'));
});

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::to('test@example.com')->send(new TestMail());
    return 'Test mail envoyé';
});


// Page de signature par le DP
// Affiche la page de signature
Route::get('bulletins/{id}/sign', [BulletinController::class, 'showSignPage'])
     ->name('bulletins.signPage');

Route::post('bulletins/{id}/sign', [BulletinController::class, 'storeSignature'])
     ->name('bulletins.sign');

     use App\Http\Controllers\UserController;

Route::post('/set-layout', [UserController::class, 'setLayout'])->name('user.setLayout');
Route::get('/set-layout', function () {
    return view('components.layout-selector');
});
Route::post('/set-accueil-display', [UserController::class, 'setAccueilDisplay'])->name('user.setAccueilDisplay');
Route::post('/set-accueil-page', [UserController::class, 'setAccueilPage'])->name('user.setAccueilPage');
Route::get('/', function () {
    // Par défaut c'est 'accueil'
    $accueil = session('accueil_page', 'accueil');
    // Sécurité : limiter aux valeurs existantes
    if (!in_array($accueil, ['accueil', 'acceuil_2'])) {
        $accueil = 'accueil';
    }
    return view('public.' . $accueil);
})->name('home');

Route::get('/settings', function () {
    return view('settings'); // Crée la vue ou fais ce que tu veux ici
})->name('settings');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // ...
});

