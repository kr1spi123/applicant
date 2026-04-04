<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clear non-numeric values to avoid truncation errors
        DB::table('specialties')->update([
            'duration' => null,
            'duration_full_time' => null,
            'duration_part_time' => null,
        ]);

        Schema::table('specialties', function (Blueprint $table) {
            $table->integer('duration')->nullable()->change();
            $table->integer('duration_full_time')->nullable()->change();
            $table->integer('duration_part_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->string('duration')->nullable()->change();
            $table->string('duration_full_time')->nullable()->change();
            $table->string('duration_part_time')->nullable()->change();
        });
    }
};
