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
          $table->enum('role',['admin','secretaire','candidat','moniteur','superAdmin','user'])->default('user');
          $table->string('cin')->unique();
          $table->string('prenom');
          $table->string('numTel');
          $table->timestamp('dateNaissance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('role');
          $table->dropColumn('cin');
            $table->dropColumn('prenom');
            $table->dropColumn('numTel');
          $table->dropColumn('dateNaissance');

        });
    }
};
