<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->string('duration')->nullable()->default(null)->change();
            $table->string('qualification')->nullable()->default(null)->change();
            $table->integer('budget_places')->nullable()->default(null)->change();
            $table->integer('total_places')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('specialties', function (Blueprint $table) {
            $table->string('duration')->nullable(false)->change();
            $table->string('qualification')->nullable(false)->change();
            $table->integer('budget_places')->nullable(false)->change();
            $table->integer('total_places')->nullable(false)->change();
        });
    }
};
