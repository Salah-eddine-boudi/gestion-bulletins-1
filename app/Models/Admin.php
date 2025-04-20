<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id_admin'; // ✅ Définit la clé primaire correcte
    public $timestamps = true; // Active les timestamps

    protected $fillable = [
        'id_user',
        'role',
        'acces',
        'tel',
        'bureau'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}


