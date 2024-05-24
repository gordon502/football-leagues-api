<?php

namespace App\Modules\Leaderboard\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Leaderboard\Model\LeaderboardCreatableInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class LeaderboardFactory implements LeaderboardFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'season_repository')]
        private readonly SeasonRepositoryInterface $seasonRepository,
        #[Autowire(service: 'season_team_repository')]
        private readonly SeasonTeamRepositoryInterface $seasonTeamRepository,
    ) {
    }

    public function create(
        LeaderboardCreatableInterface $leaderboardCreatable,
        string $modelClass
    ): LeaderboardInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(LeaderboardInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . LeaderboardInterface::class
            );
        }

        /** @var LeaderboardInterface $model */
        $model = new $modelClass();

        $model
            ->setPlace($leaderboardCreatable->getPlace())
            ->setMatchesPlayed($leaderboardCreatable->getMatchesPlayed())
            ->setPoints($leaderboardCreatable->getPoints())
            ->setWins($leaderboardCreatable->getWins())
            ->setDraws($leaderboardCreatable->getDraws())
            ->setLosses($leaderboardCreatable->getLosses())
            ->setGoalsScored($leaderboardCreatable->getGoalsScored())
            ->setGoalsConceded($leaderboardCreatable->getGoalsConceded())
            ->setHomeGoalsScored($leaderboardCreatable->getHomeGoalsScored())
            ->setHomeGoalsConceded($leaderboardCreatable->getHomeGoalsConceded())
            ->setAwayGoalsScored($leaderboardCreatable->getAwayGoalsScored())
            ->setAwayGoalsConceded($leaderboardCreatable->getAwayGoalsConceded())
            ->setPromotedToHigherDivision($leaderboardCreatable->isPromotedToHigherDivision())
            ->setEligibleForPromotionBargaining($leaderboardCreatable->isEligibleForPromotionBargaining())
            ->setEligibleForRetentionBargaining($leaderboardCreatable->isEligibleForRetentionBargaining())
            ->setRelegatedToLowerDivision($leaderboardCreatable->isRelegatedToLowerDivision())
            ->setDirectMatchesPlayed($leaderboardCreatable->getDirectMatchesPlayed())
            ->setDirectMatchesPoints($leaderboardCreatable->getDirectMatchesPoints())
            ->setDirectMatchesWins($leaderboardCreatable->getDirectMatchesWins())
            ->setDirectMatchesDraws($leaderboardCreatable->getDirectMatchesDraws())
            ->setDirectMatchesLosses($leaderboardCreatable->getDirectMatchesLosses())
            ->setDirectMatchesGoalsScored($leaderboardCreatable->getDirectMatchesGoalsScored())
            ->setDirectMatchesGoalsConceded($leaderboardCreatable->getDirectMatchesGoalsConceded())
            ->setAnnotation($leaderboardCreatable->getAnnotation());

        $season = $this->seasonRepository->findById(
            $leaderboardCreatable->getSeasonId()
        );
        if (!$season) {
            throw new RelatedEntityNotFoundException(
                'Season not found.'
            );
        }

        $seasonTeam = $this->seasonTeamRepository->findById(
            $leaderboardCreatable->getSeasonTeamId()
        );
        if (!$seasonTeam) {
            throw new RelatedEntityNotFoundException(
                'Season team not found.'
            );
        }

        $model
            ->setSeason($season)
            ->setSeasonTeam($seasonTeam);

        return $model;
    }
}
