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

        // 1. EGE Score (max 300, usually)
        $rating += $application->ege_score;

        // 2. Certificate Score (GPA 3.0 - 5.0)
        // Convert GPA to a comparable scale (e.g., * 20 => max 100)
        $rating += $application->certificate_score * 20;

        // 3. Achievements (Bonus points)
        if ($application->has_achievements) {
            $rating += 10;
        }

        $application->rating = $rating;
        $application->save();

        // Optional: Recalculate status based on ranking
        // This would require checking available budget places vs current position
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