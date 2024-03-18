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

      Schema::table('examens', function (Blueprint $table) {
        $table->dropForeign(['resultat_id']); // Remplacez 'resultat_id' par le nom de votre clé étrangère
    });
      Schema::dropIfExists('resultats');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
    Schema::table('examens', function (Blueprint $table) {
      $table->foreign('resultat_id')->references('id')->on('resultats');
  });
  Schema::create('resultats', function (Blueprint $table) {
    $table->string('type_resultat');
});
    }
};
