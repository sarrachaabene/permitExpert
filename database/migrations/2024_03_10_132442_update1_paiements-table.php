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
      Schema::table('paiements', function (Blueprint $table) {
        // Supprimer la contrainte de clé étrangère
        
        // Supprimer la colonne user_id
        $table->dropColumn('auto_ecole_id');
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
