<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            // Duration per form
            $table->string('duration_full_time')->nullable()->after('duration');
            $table->string('duration_part_time')->nullable()->after('duration_full_time');
            $table->string('duration_distance')->nullable()->after('duration_part_time');

            // Qualification per form
            $table->string('qualification_full_time')->nullable()->after('qualification');
            $table->string('qualification_part_time')->nullable()->after('qualification_full_time');
            $table->string('qualification_distance')->nullable()->after('qualification_part_time');

            // Budget places per form
            $table->integer('budget_places_full_time')->nullable()->after('budget_places');
            $table->integer('budget_places_part_time')->nullable()->after('budget_places_full_time');
            $table->integer('budget_places_distance')->nullable()->after('budget_places_part_time');

            // Total places per form
            $table->integer('total_places_full_time')->nullable()->after('total_places');
            $table->integer('total_places_part_time')->nullable()->after('total_places_full_time');
            $table->integer('total_places_distance')->nullable()->after('total_places_part_time');
        });
    }

    public function down(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->dropColumn([
                'duration_full_time', 'duration_part_time', 'duration_distance',
                'qualification_full_time', 'qualification_part_time', 'qualification_distance',
                'budget_places_full_time', 'budget_places_part_time', 'budget_places_distance',
                'total_places_full_time', 'total_places_part_time', 'total_places_distance',
            ]);
        });
    }
};
