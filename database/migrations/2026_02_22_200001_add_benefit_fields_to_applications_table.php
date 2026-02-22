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
            if (!Schema::hasColumn('applications', 'benefits')) {
                $table->json('benefits')->nullable()->after('has_achievements');
            }
            if (!Schema::hasColumn('applications', 'benefit_proof')) {
                $table->string('benefit_proof')->nullable()->after('benefits');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'benefits')) {
                $table->dropColumn('benefits');
            }
            if (Schema::hasColumn('applications', 'benefit_proof')) {
                $table->dropColumn('benefit_proof');
            }
        });
    }
};

