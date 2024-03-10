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
            $table->time('heureD')->nullable()->change();
            $table->time('heureF')->nullable()->change();
            $table->time('heureF')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('examens', function (Blueprint $table) {
        $table->time('heureD')->nullable(false)->change();
        $table->time('heureF')->nullable(false)->change();
    });
}
};
