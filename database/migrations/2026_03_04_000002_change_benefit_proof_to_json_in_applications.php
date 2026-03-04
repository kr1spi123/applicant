<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Конвертируем существующие данные: строку -> json-массив
        DB::table('applications')
            ->whereNotNull('benefit_proof')
            ->where('benefit_proof', 'not like', '[%')
            ->get()
            ->each(function ($row) {
                DB::table('applications')
                    ->where('id', $row->id)
                    ->update(['benefit_proof' => json_encode([$row->benefit_proof])]);
            });

        Schema::table('applications', function (Blueprint $table) {
            $table->json('benefit_proof')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('benefit_proof')->nullable()->change();
        });
    }
};
