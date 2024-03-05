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
      Schema::table('notifications', function (Blueprint $table) {
      $table->foreignId('sender_msg')->nullable()->constrained();
      $table->foreignId('receptient_msg')->nullable()->constrained();
      $table->foreignId('message_description')->nullable()->constrained();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('notifications', function (Blueprint $table) {
        $table->dropForeign(['sender_msg']);
        $table->dropForeign(['receptient_msg']);
        $table->dropForeign(['message_description']);
    });
    }
};
