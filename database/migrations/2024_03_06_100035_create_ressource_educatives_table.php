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
        Schema::create('ressource_educatives', function (Blueprint $table) {
            $table->id();
            $table->string('titreR');
            $table->string('descriptionR');
            $table->string('typeR');
            $table->date('dateD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressource_educatives');
    }
};
