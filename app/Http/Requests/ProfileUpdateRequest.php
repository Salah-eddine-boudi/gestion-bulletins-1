<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        // Retourne true si vous n'avez pas de logique d'autorisation particulière
        return true;
    }

    /**
     * Règles de validation pour la mise à jour du profil.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Champ : name
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            // Champ : email
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Vérifie l'unicité de l'email tout en ignorant l'utilisateur courant
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Champ : tel_pro (Téléphone professionnel)
            'tel_pro' => [
                'nullable',
                'string',
                'max:30',
            ],

            // Champ : statut
            'statut' => [
                'nullable',
                'string',
                Rule::in(['actif', 'inactif']), // Ajustez la liste si nécessaire
            ],

            // Champ : photo
            'photo' => [
                'nullable',
                'image',       // Valide le fichier comme une image
                'mimes:jpeg,png,jpg,gif', // Formats autorisés
                'max:2048',    // Taille max : 2 Mo
            ],

            // Champ : role
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'professeur', 'eleve', 'directeur']), 
                // Ajoutez ou retirez les valeurs selon vos besoins
            ],
        ];
    }
}
