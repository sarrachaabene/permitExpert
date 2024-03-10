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
        Schema::table('vehicules', function (Blueprint $table) {
            // Supprimer la contrainte étrangère
            $table->dropForeign(['Examen_id']);
            // Supprimer la colonne
            $table->dropColumn('Examen_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si vous devez recréer la colonne et la contrainte étrangère lors de la migration descendante, vous pouvez le faire ici.
    }
};