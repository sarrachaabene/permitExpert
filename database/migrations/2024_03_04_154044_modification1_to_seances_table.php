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
          $table->enum('typee',['code','circuit','parc'])->nullable('false')->change();

        ////  $table->time('heureDebut')->nullable()->change();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
         $table->dropColumn('typee')->change();
        
        });
    }
};
