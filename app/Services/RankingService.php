<?php

namespace App\Services;

use App\Models\Application;

class RankingService
{
    /**
     * Calculate and update the rating for an application.
     */
    public function calculateRating(Application $application): void
    {
        $rating = 0;

        $rating += $application->ege_score;

        $rating += $application->certificate_score * 20;

        if ($application->has_achievements) {
            $rating += 10;
        }

        $application->rating = $rating;
        $application->save();

    }

    /**
     * Get the position of the applicant in the list for their specialty.
     */
    public function getPosition(Application $application): int
    {
        return Application::where('specialty_id', $application->specialty_id)
            ->where(function ($q) use ($application) {
                $q->where('rating', '>', $application->rating)
                  ->orWhere(function ($q2) use ($application) {
                      $q2->where('rating', $application->rating)
                         ->where('created_at', '<', $application->created_at);
                  });
            })
            ->count() + 1;
    }
}