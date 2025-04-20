<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la colonne name
            $table->dropColumn('name');

            // Ajouter les colonnes nom et prenom
            $table->string('nom')->after('id');
            $table->string('prenom')->after('nom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes nom et prenom
            $table->dropColumn(['nom', 'prenom']);

            // Réajouter la colonne name
            $table->string('name')->after('id');
        });
    }
};
