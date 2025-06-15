<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function setLayout(Request $request)
{
    $layouts = ['app', 'admin', 'admin2'];

    $request->validate([
        'layout' => ['required', Rule::in($layouts)],
    ]);

    $layoutChoisi = $request->layout;

    // On vérifie une dernière fois par sécurité
    if (!in_array($layoutChoisi, $layouts)) {
        $layoutChoisi = 'app';
    }

    session(['layout' => $layoutChoisi]);

    return redirect()->back()->with('status', 'Layout changé avec succès !');
}

    
    
public function setAccueilPage(Request $request)
{
    $request->validate([
        'accueil_page' => 'required|in:accueil,acceuil_2'
    ]);
    session(['accueil_page' => $request->accueil_page]);
    return back()->with('status', "Page d'accueil changée !");
}


}
