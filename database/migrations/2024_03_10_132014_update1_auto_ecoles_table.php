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
        // Supprimer la contrainte de clé étrangère
        $table->dropForeign(['user_id']);
        // Supprimer la colonne user_id
        $table->dropColumn('user_id');
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
