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
      Schema::table('resultats', function (Blueprint $table) {

        $table->foreignId('permis_id')->nullable()->constrained();});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('resultats', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['permis_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('permis_id');
    });
    }
};
