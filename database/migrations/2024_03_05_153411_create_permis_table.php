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
        Schema::create('permis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('categorie', ['Permis_A1', 'Permis_A', 'Permis_B', 'Permis_B_E', 'Permis_C', 'Permis_C_E', 'Permis_D', 'Permis_D_E', 'Permis_D1', 'Permis_H'])->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permis');
    }
};
