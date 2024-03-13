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
        // Supprimer la contrainte étrangère
        $table->dropForeign(['transaction_id']);
        // Supprimer la colonne
        $table->dropColumn('transaction_id'); 
    });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recreate the column
            $table->unsignedBigInteger('transaction_id');
            // Recreate the foreign key constraint
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }
};
