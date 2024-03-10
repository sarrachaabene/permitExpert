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
        $table->foreignId('Examen_id')->nullable()->constrained();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('vehicules', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['Examen_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('Examen_id');
    });
    }
};
