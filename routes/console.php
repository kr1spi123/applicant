<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:import-sqlite', function () {
    config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
    DB::purge('sqlite');
    $sqlite = DB::connection('sqlite');
    $mysql = DB::connection('mysql');

    $tables = [
        'specialties' => ['id', 'name', 'code', 'duration', 'budget_places', 'description', 'qualification', 'skills', 'photo', 'created_at', 'updated_at'],
        'users' => ['id', 'name', 'email', 'password', 'phone', 'role', 'remember_token', 'created_at', 'updated_at'],
        'applications' => ['id', 'user_id', 'specialty_id', 'full_name', 'phone', 'email', 'birthdate', 'street', 'house', 'postal_code', 'school', 'graduation_year', 'certificate_file', 'ege_score', 'certificate_score', 'has_achievements', 'rating', 'status', 'qr_code_path', 'created_at', 'updated_at'],
        'audit_logs' => ['id', 'user_id', 'action', 'details', 'ip_address', 'created_at', 'updated_at'],
    ];

    foreach ($tables as $table => $columns) {
        if (!$sqlite->getSchemaBuilder()->hasTable($table)) {
            $this->error("SQLite table {$table} not found");
            continue;
        }

        $rows = $sqlite->table($table)->get($columns)->map(function ($row) {
            return (array) $row;
        })->toArray();

        if (empty($rows)) {
            $this->info("No rows to import for {$table}");
            continue;
        }

        $mysql->table($table)->upsert(
            $rows,
            ['id'],
            array_values(array_diff($columns, ['id']))
        );

        $count = count($rows);
        $this->info("Imported {$count} rows into {$table}");
    }

    $this->info('Import completed');
})->purpose('Import data from SQLite database/database.sqlite into MySQL');
