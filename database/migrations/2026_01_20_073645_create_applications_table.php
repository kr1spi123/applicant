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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialty_id')->constrained()->onDelete('cascade');
            
            // Personal Info
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->date('birthdate');
            
            // Address
            $table->string('street');
            $table->string('house');
            $table->string('postal_code');
            
            // Education
            $table->string('school');
            $table->year('graduation_year');
            $table->string('certificate_file')->nullable(); // Path to file
            
            // Ranking Factors (Killer Feature #1)
            $table->integer('ege_score')->default(0);
            $table->float('certificate_score')->default(0.0);
            $table->boolean('has_achievements')->default(false);
            $table->json('benefits')->nullable();
            $table->string('benefit_proof')->nullable();
            $table->float('rating')->default(0.0); // Calculated score
            
            // Status & Features
            $table->string('status')->default('new'); // new, approved, rejected, reserve
            $table->string('qr_code_path')->nullable(); // Killer Feature #2
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
