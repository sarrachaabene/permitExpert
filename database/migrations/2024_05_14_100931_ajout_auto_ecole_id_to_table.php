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
      Schema::table('examens', function (Blueprint $table) {
        $table->foreignId('auto_ecole_id')->nullable()->constrained();

    }); 
     Schema::table('seances', function (Blueprint $table) {
      $table->foreignId('auto_ecole_id')->nullable()->constrained();

  });
  Schema::table('vehicules', function (Blueprint $table) {
    $table->foreignId('auto_ecole_id')->nullable()->constrained();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('examens', function (Blueprint $table) {
        $table->dropForeign(['auto_ecole_id']);
        $table->dropColumn('auto_ecole_id');
    }); 
     Schema::table('seances', function (Blueprint $table) {
      $table->dropForeign(['auto_ecole_id']);
      $table->dropColumn('auto_ecole_id');
  });
    Schema::table('vehicules', function (Blueprint $table) {
    $table->dropForeign(['auto_ecole_id']);
    $table->dropColumn('auto_ecole_id');
});
    }
};
