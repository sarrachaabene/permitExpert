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
        // Supprimer la contrainte étrangère
        $table->dropForeign(['vehicule_id']);
        // Supprimer la colonne
        $table->dropColumn('vehicule_id');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
