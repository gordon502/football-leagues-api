<?php

namespace App\Modules\SeasonTeam\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamCreatableInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\Team\Repository\TeamRepositoryInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class SeasonTeamFactory implements SeasonTeamFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'team_repository')]
        private TeamRepositoryInterface $teamRepository,
        #[Autowire(service: 'season_repository')]
        private SeasonRepositoryInterface $seasonRepository,
    ) {
    }

    public function create(
        SeasonTeamCreatableInterface $seasonTeamCreatable,
        string $modelClass
    ): SeasonTeamInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(SeasonTeamInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . SeasonTeamInterface::class
            );
        }

        /** @var SeasonTeamInterface $model */
        $model = new $modelClass();

        $model->setName($seasonTeamCreatable->getName());

        $team = $this->teamRepository->findById($seasonTeamCreatable->getTeamId());
        if (!$team) {
            throw new RelatedEntityNotFoundException(
                'Team not found.'
            );
        }
        $model->setTeam($team);

        $season = $this->seasonRepository->findById($seasonTeamCreatable->getSeasonId());
        if (!$season) {
            throw new RelatedEntityNotFoundException(
                'Season not found.'
            );
        }
        $model->setSeason($season);

        return $model;
    }
}
