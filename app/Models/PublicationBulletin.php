<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationBulletin extends Model
{
    // Si votre PK est bien "id", on n'a pas besoin de redéfinir $primaryKey
    // public $primaryKey = 'id';
    // public $incrementing = true;
    // protected $keyType = 'int';

    // Active automatiquement created_at / updated_at
    public $timestamps = true;

    // Table associée
    protected $table = 'bulletin_publications';

    // Champs autorisés en assignation de masse
    protected $fillable = [
        'eleve_id',
        'semestre',
        'annee_universitaire',
        'is_authorized_by_dp',
        'authorized_by',
        'authorized_at',
        'date_generation',
    ];

    /**
     * Relation vers l'élève.
     */
    public function eleve(): BelongsTo
    {
        // 'id_eleve' est la PK de la table eleves
        return $this->belongsTo(Eleve::class, 'eleve_id', 'id_eleve');
    }

    /**
     * Relation vers l'utilisateur qui a autorisé.
     */
    public function authorizer(): BelongsTo
    {
        // 'id_user' est la PK de la table users
        return $this->belongsTo(User::class, 'authorized_by', 'id_user');
    }
}
