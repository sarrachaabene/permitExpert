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
        Schema::table('demande_inscriptions', function (Blueprint $table) {
          $table->enum('status',['en attente','confirmee','refusee'])->default('en attente')->change();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demande_inscriptions', function (Blueprint $table) {
            //
        });
    }
};
