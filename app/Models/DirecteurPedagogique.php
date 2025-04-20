<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirecteurPedagogique extends Model
{
    // Spécifier le nom de la table si elle est personnalisée
    protected $table = 'directeurs_pedagogiques';

    // Définir la clé primaire
    protected $primaryKey = 'id_dp'; // Spécifier la clé primaire, assurez-vous qu'elle existe dans la table

    // Si vous ne souhaitez pas utiliser les timestamps (created_at, updated_at)
    // Si vous utilisez des colonnes 'created_at' et 'updated_at', supprimez cette ligne
    public $timestamps = false;

    // Les colonnes que vous pouvez remplir via le modèle
    protected $fillable = [
        'id_user',  // Assurez-vous que la colonne dans la base de données est 'id_user'
        'id_prof',
        'date_prise_fonction',
        'date_fin_mandat',
        'tel',
        'bureau',
        'appreciation',
    ];

    /**
     * Définir la relation avec le modèle User.
     * Un directeur pédagogique appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');  // Assurez-vous que 'id_user' est bien la colonne dans votre table
    }

    /**
     * Définir la relation avec le modèle Professeur.
     * Un directeur pédagogique peut avoir un professeur associé.
     */
    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_prof');  // La relation avec Professeur, assurez-vous que 'id_prof' existe dans la table
    }
}
