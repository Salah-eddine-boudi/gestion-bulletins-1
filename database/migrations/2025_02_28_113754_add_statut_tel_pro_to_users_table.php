<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajoute le champ tel_pro après 'password'
            $table->string('tel_pro')->nullable()->after('password');
            // Ajoute le champ statut après 'tel_pro'
            $table->string('statut')->default('actif')->after('tel_pro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tel_pro');
            $table->dropColumn('statut');
        });
    }
};
