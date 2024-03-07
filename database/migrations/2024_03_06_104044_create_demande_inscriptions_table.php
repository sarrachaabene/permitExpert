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
        Schema::create('demande_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('nomEcole');
            $table->string('aderesseEcole');
            $table->string('descriptionEcole');
            $table->string('imageEcole');
            $table->string('nomA');
            $table->string('prenomA');
            $table->string('emailA');
            $table->string('passwordA');
            $table->string('cin');
            $table->string('numTel');
            $table->string('imageA');
            $table->string('dateNaissance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_inscriptions');
    }
};
