<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesseursTable extends Migration
{
    public function up()
    {
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id('id_prof');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('adresse');
            $table->string('matricule');
            $table->string('grade');
            $table->string('regime_emploi');
            $table->string('specialite')->nullable();
            $table->date('date_prise_fonction');
            $table->date('date_fin_mandat')->nullable();
            $table->text('affiliation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('professeurs');
    }
}
