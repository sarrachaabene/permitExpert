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

        Schema::table('auto_ecoles', function (Blueprint $table) {
          $table->dropColumn('autoecole_image');
        });
        Schema::table('demande_inscriptions', function (Blueprint $table) {
          $table->dropColumn('imageEcole');
          $table->dropColumn('nomA');
          $table->dropColumn('PrenomA');
          $table->string('user_nameA');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('AutoEcole', function (Blueprint $table) {
        $table->string('autoecole_image')->nullable();
      });  Schema::table('DemandeInscription', function (Blueprint $table) {
      //
  });
    }
};
