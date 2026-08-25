<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\PointLog;
use App\Models\User;
use App\Models\UserBadge;

class PointService
{
    public const POINTS_CLASS_HADIR = 10;

    public const POINTS_EVENT_HADIR = 15;

    public const POINTS_DONATION_CONFIRMED = 20;

    public function award(User $user, string $sourceType, int $sourceId, int $points): void
    {
        $alreadyAwarded = PointLog::where('user_id', $user->id)
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->exists();

        if ($alreadyAwarded) {
            return;
        }

        PointLog::create([
            'user_id' => $user->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'points' => $points,
        ]);

        $this->checkBadges($user);
    }

    public function checkBadges(User $user): void
    {
        $totalPoints = $user->totalPoints();

        Badge::all()->each(function (Badge $badge) use ($user, $totalPoints) {
            $criteria = $badge->criteria_json ?? [];

            if (($criteria['type'] ?? null) !== 'points_threshold') {
                return;
            }

            if ($totalPoints < (int) ($criteria['value'] ?? PHP_INT_MAX)) {
                return;
            }

            UserBadge::firstOrCreate(
                ['user_id' => $user->id, 'badge_id' => $badge->id],
                ['earned_at' => now()]
            );
        });
    }
}
