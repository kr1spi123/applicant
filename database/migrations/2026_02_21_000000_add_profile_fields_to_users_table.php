<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable()->after('name');
            $table->date('birthdate')->nullable()->after('surname');
            $table->string('street')->nullable()->after('phone');
            $table->string('house')->nullable()->after('street');
            $table->string('postal_code')->nullable()->after('house');
            $table->string('school')->nullable()->after('postal_code');
            $table->unsignedSmallInteger('graduation_year')->nullable()->after('school');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'birthdate',
                'street',
                'house',
                'postal_code',
                'school',
                'graduation_year',
            ]);
        });
    }
};

