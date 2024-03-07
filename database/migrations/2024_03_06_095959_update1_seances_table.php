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
      $table->foreignId('moniteur_id')->constrained('users')->where('role', 'moniteur'); // Clé étrangère restreinte aux moniteurs
      $table->foreignId('candidat_id')->constrained('users')->where('role', 'candidat');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('seances', function (Blueprint $table) {
        $table->dropForeign(['moniteur_id']);
        $table->dropForeign(['candidat_id']);
    });
    }
};
