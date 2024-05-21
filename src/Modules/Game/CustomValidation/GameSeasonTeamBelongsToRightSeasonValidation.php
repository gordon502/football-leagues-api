<?php

namespace App\Modules\Game\CustomValidation;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Modules\Game\Exception\WrongSeasonTeamSelectedException;
use App\Modules\Game\Model\GameInterface;
use InvalidArgumentException;
use ReflectionClass;

final class GameSeasonTeamBelongsToRightSeasonValidation implements CustomValidationInterface
{
    public function validate($value): void
    {
        $reflection = new ReflectionClass($value);

        if (!$reflection->implementsInterface(GameInterface::class)) {
            throw new InvalidArgumentException('Game must implement GameInterface');
        }

        /** @var GameInterface $value */

        $season = $value->getRound()->getSeason();

        if ($value->getSeasonTeam1() && $value->getSeasonTeam1()->getSeason()->getId() !== $season->getId()) {
            throw new WrongSeasonTeamSelectedException(
                'Season team 1 does not belong to the season assigned with given round'
            );
        }

        if ($value->getSeasonTeam2() && $value->getSeasonTeam2()->getSeason()->getId() !== $season->getId()) {
            throw new WrongSeasonTeamSelectedException(
                'Season team 2 does not belong to the right season'
            );
        }
    }
}
