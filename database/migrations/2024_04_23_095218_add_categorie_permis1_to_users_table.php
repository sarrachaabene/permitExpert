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
          $table->enum('cat_permis', ['Permis_A1', 'Permis_A', 'Permis_B', 'Permis_B_E', 'Permis_C', 'Permis_C_E', 'Permis_D', 'Permis_D_E', 'Permis_D1', 'Permis_H'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
