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
      Schema::create('seances', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['code', 'circuit', 'parc']);
        $table->time('heureD')->nullable();
        $table->time('heureF')->nullable();
        $table->date('dateS')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
