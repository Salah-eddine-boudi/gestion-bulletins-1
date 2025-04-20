<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id('id_eleve');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('date_naissance');
            $table->decimal('matricule')->unique();
            $table->string('niveau');
            $table->string('specialite')->nullable();
            $table->date('date_inscription');
            $table->string('photo_identite')->nullable();
            $table->string('status')->default('actif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eleves');
    }
};
