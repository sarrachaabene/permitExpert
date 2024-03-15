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
         $table->string('password')->nullable()->change();
         $table->string('user_image')->nullable(); 

        });
        Schema::table('auto_ecoles', function (Blueprint $table) {
         $table->string('autoecole_image')->nullable(); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('user_image');
      $table->string('password')->notNullable()->change();
 });

   Schema::table('auto_ecoles', function (Blueprint $table) {
     $table->dropColumn('autoecole_image');
        });
    }
};
