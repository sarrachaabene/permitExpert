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
        Schema::table('messages', function (Blueprint $table) {
          $table->unsignedBigInteger('sender_id');
          $table->unsignedBigInteger('recipient_id');
          $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('messages');

    }
};
