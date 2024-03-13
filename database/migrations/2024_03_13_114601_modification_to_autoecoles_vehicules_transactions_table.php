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
        // Drop the foreign key constraint
        $table->dropForeign(['transaction_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('transaction_id');
    });
    Schema::table('vehicules', function (Blueprint $table) {
      // Drop the foreign key constraint
      $table->dropForeign(['transaction_id']);
      // Drop the auto_ecole_id column
      $table->dropColumn('transaction_id');
  });
  Schema::table('transactions', function (Blueprint $table) {
    $table->foreignId('vehicule_id')->nullable()->constrained();
  });
  Schema::table('transactions', function (Blueprint $table) {
    $table->foreignId('auto_ecole_id')->nullable()->constrained();
  });
    }
    
  
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('auto_ecoles', function (Blueprint $table) {
        $table->foreignId('transaction_id')->nullable()->constrained();
      });
      Schema::table('vehicules', function (Blueprint $table) {
        $table->foreignId('transaction_id')->nullable()->constrained();
      });
      Schema::table('transactions', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['vehicule_id']);
        // Drop the auto_ecole_id column
        $table->dropColumn('vehicule_id');
    });
    Schema::table('transactions', function (Blueprint $table) {
      // Drop the foreign key constraint
      $table->dropForeign(['auto_ecole_id']);
      // Drop the auto_ecole_id column
      $table->dropColumn('auto_ecole_id');
  });
    }
};
