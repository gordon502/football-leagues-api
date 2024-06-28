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

    public function validate($value, array $customOptions = []): void
    {
        $reflection = new ReflectionClass($value);

        if (!$reflection->implementsInterface(LeaderboardInterface::class)) {
            throw new InvalidArgumentException('Leaderboard must implement LeaderboardInterface');
        }

        /** @var LeaderboardInterface $value */
        $seasonTeam = $value->getSeasonTeam();

        /** @var LeaderboardInterface[] $batchUpdateOtherItems */
        $batchUpdateOtherItems = array_filter(
            $customOptions['batchUpdateItems'] ?? [],
            fn ($item) => $item instanceof LeaderboardInterface && $item->getId() !== $value->getId()
        );

        /** @var LeaderboardInterface[] $leaderboardsForSeasonTeam */
        $leaderboardsForSeasonTeamFromDatabase = $this->leaderboardRepository->findAllForSeasonTeam($seasonTeam);
        $leaderboardsForSeasonTeamFromBatchUpdate = array_filter(
            $batchUpdateOtherItems,
            fn (LeaderboardInterface $leaderboard) => $leaderboard->getSeasonTeam()->getId() === $seasonTeam->getId()
        );

        /** @var array<string, LeaderboardInterface> $leaderboardsToCheckWith */
        $leaderboardsToCheckWith = [];
        foreach ($leaderboardsForSeasonTeamFromBatchUpdate as $leaderboard) {
            $leaderboardsToCheckWith[$leaderboard->getId()] = $leaderboard;
        }
        foreach ($leaderboardsForSeasonTeamFromDatabase as $leaderboard) {
            if (!isset($leaderboardsToCheckWith[$leaderboard->getId()])) {
                $leaderboardsToCheckWith[$leaderboard->getId()] = $leaderboard;
            }
        }

        $leaderboardsToCheckWith = array_values($leaderboardsToCheckWith);

        $count = count($leaderboardsToCheckWith);

        if ($count === 0) {
            return;
        }

        if ($count === 1) {
            $leaderboard = $leaderboardsToCheckWith[0];
            if ($leaderboard->getId() === $value->getId()) {
                return;
            }
        }

        throw new SeasonTeamAlreadyOnLeaderboardException();
    }
}
