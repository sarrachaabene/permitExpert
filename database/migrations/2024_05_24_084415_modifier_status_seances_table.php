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
      Schema::table('seances', function (Blueprint $table) {
        $table->enum('candidat_status',['en attente','confirmee','refusee'])->change();
        $table->enum('moniteur_status',['en attente','confirmee','refusee'])->change();

    }); 
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users', 'autorite_de_delivrance')) {
          $table->string('autorite_de_delivrance');
      }
  });
  Schema::table('demande_inscriptions', function (Blueprint $table) {
    if (Schema::hasColumn('demande_inscriptions', 'imageA')) {
        $table->dropColumn('imageA');
    }
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'autorite_de_delivrance')) {
            $table->dropColumn('autorite_de_delivrance');
        }
    });

    Schema::table('demande_inscriptions', function (Blueprint $table) {
      if (!Schema::hasColumn('demande_inscriptions', 'imageA')) {
          $table->string('imageA');
      }
  });


    }
};
