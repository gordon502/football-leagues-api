<?php

namespace App\Modules\Leaderboard\CustomValidation;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Modules\Leaderboard\Exception\WrongSeasonTeamSelectedException;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use InvalidArgumentException;
use ReflectionClass;

final class LeaderboardSeasonAndTeamRelatedValidation implements CustomValidationInterface
{
    public function validate($value, array $customOptions = []): void
    {
        $reflection = new ReflectionClass($value);

        if (!$reflection->implementsInterface(LeaderboardInterface::class)) {
            throw new InvalidArgumentException('Leaderboard must implement Leaderboard');
        }

        /** @var LeaderboardInterface $value */

        if ($value->getSeasonTeam()->getSeason()->getId() !== $value->getSeason()->getId()) {
            throw new WrongSeasonTeamSelectedException(
                'Season team does not belong to the season assigned with given leaderboard'
            );
        }
    }
}
