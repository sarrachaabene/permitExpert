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
            $table->softDeletes(); // Ajoutez cette ligne pour créer la colonne 'deleted_at'
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropSoftDeletes(); // Si vous souhaitez annuler la migration, vous pouvez supprimer la colonne 'deleted_at'

        });
    }
};
