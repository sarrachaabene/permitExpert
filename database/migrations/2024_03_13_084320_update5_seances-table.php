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

           $table->unsignedBigInteger('candidat_id');
           $table->unsignedBigInteger('moniteur_id');
           $table->foreign('candidat_id')->references('id')->on('users');
   
           $table->foreign('moniteur_id')->references('id')->on('users');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      $table->dropForeign(['candidat_id']);
      $table->dropForeign(['moniteur_id']);
    }
};
