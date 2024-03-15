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
          $table->renameColumn('candidat_accepte', 'candidat_status');
          $table->renameColumn('moniteur_accepte', 'moniteur_status');

        });

        Schema::table('demande_inscriptions', function (Blueprint $table) { 
          $table->dropColumn('passwordA');
          $table->boolean('status')->default(false);






      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
          $table->string('candidat_status')->nullable('false')->change();
          $table->string('moniteur_status')->nullable('false')->change();

        });

        Schema::table('demande_inscriptions', function (Blueprint $table) {
          $table->string('passwordA')->nullable();// Assuming the dropped column was nullable
          $table->dropColumn('status'); 
      });
    }
};
