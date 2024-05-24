<?php

namespace App\Modules\Leaderboard\CustomValidation;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Modules\Leaderboard\Exception\SeasonTeamAlreadyOnLeaderboardException;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Repository\LeaderboardRepositoryInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class SeasonTeamOnlyOnOneLeaderboardValidation implements CustomValidationInterface
{
    public function __construct(
        #[Autowire(service: 'leaderboard_repository')]
        private LeaderboardRepositoryInterface $leaderboardRepository
    ) {
    }

    public function validate($value): void
    {
        $reflection = new ReflectionClass($value);

        if (!$reflection->implementsInterface(LeaderboardInterface::class)) {
            throw new InvalidArgumentException('Leaderboard must implement LeaderboardInterface');
        }

        /** @var LeaderboardInterface $value */
        $seasonTeam = $value->getSeasonTeam();

        /** @var LeaderboardInterface[] $leaderboardsForSeasonTeam */
        $leaderboardsForSeasonTeam = $this->leaderboardRepository->findAllForSeasonTeam($seasonTeam);

        $count = count($leaderboardsForSeasonTeam);

        if ($count === 0) {
            return;
        }

        if ($count === 1) {
            $leaderboard = $leaderboardsForSeasonTeam[0];
            if ($leaderboard->getId() === $value->getId()) {
                return;
            }
        }

        throw new SeasonTeamAlreadyOnLeaderboardException();
    }
}
