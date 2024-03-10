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
      {       Schema::table('users', function (Blueprint $table) {

        $table->foreignId('seance_id')->nullable()->constrained();});
  }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['seance_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('seance_id');
    });
    }
};
