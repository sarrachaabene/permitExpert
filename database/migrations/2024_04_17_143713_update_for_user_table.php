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
        $table->dropColumn('name');
        $table->dropColumn('prenom');
        $table->string('user_name');
    
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('users', function (Blueprint $table) {
        $table->string('name')->after('id');
        $table->string('prenom')->after('name');
        $table->dropColumn('user_name');
    });
    }
};
