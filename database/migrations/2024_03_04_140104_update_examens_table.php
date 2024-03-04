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
      Schema::table('examens', function (Blueprint $table) {
      
        $table->datetime('heureD')->change();// Utilisez datetime au lieu de date
        $table->datetime('heureF')->change();
      
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         $table->dropColumn('heureD')->change();;
         $table->dropColumn('heureF')->change();;

    }
};
