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

        $table->foreignId('vehicule_id')->nullable()->constrained();}); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('seances', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['vehicule_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('vehicule_id');
    });
    }
};
