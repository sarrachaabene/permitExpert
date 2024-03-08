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
        $table->foreignId('seance_id')->nullable()->constrained('seances')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    
      
          Schema::table('users', function (Blueprint $table) {
              // Supprimer la colonne seance_id et la contrainte de clé étrangère associée
              $table->dropForeign(['seance_id']);
              $table->dropColumn('seance_id');
          });
      
      
    }
};
