<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->decimal('cost_full_time', 10, 2)->nullable()->after('skills');
            $table->decimal('cost_part_time', 10, 2)->nullable()->after('cost_full_time');
            $table->decimal('cost_distance', 10, 2)->nullable()->after('cost_part_time');
        });
    }

    public function down(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->dropColumn(['cost_full_time', 'cost_part_time', 'cost_distance']);
        });
    }
};

