<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Services\RankingService;
use Illuminate\Console\Command;

class RecalculateRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Пересчитывает рейтинги для всех заявок';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rankingService = app(RankingService::class);
        $applications = Application::all();

        $this->info("Найдено заявок: {$applications->count()}");

        foreach ($applications as $application) {
            $rankingService->calculateRating($application);
            $this->info("Заявка #{$application->id} рейтинг обновлен: {$application->rating}");
        }

        $this->info('Все рейтинги пересчитаны!');
    }
}
