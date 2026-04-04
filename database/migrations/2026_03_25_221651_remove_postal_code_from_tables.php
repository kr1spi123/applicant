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
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('postal_code');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('postal_code')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code')->nullable();
        });
    }
};
