<?php

namespace App\Modules\Game\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Game\Model\GameCreatableInterface;
use App\Modules\Game\Model\GameInterface;
use App\Modules\Round\Repository\RoundRepositoryInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use DateTime;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class GameFactory implements GameFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'season_team_repository')]
        private SeasonTeamRepositoryInterface $seasonTeamRepository,
        #[Autowire(service: 'round_repository')]
        private RoundRepositoryInterface $roundRepository
    ) {
    }

    public function create(
        GameCreatableInterface $matchCreatable,
        string $modelClass
    ): GameInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(GameInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . GameInterface::class
            );
        }

        /** @var GameInterface $model */
        $model = new $modelClass();

        $model->setDate(DateTime::createFromFormat('Y-m-d H:i:s', $matchCreatable->getDate()));
        $model->setStadium($matchCreatable->getStadium());
        $model->setTeam1ScoreHalf($matchCreatable->getTeam1ScoreHalf());
        $model->setTeam2ScoreHalf($matchCreatable->getTeam2ScoreHalf());
        $model->setTeam1Score($matchCreatable->getTeam1Score());
        $model->setTeam2Score($matchCreatable->getTeam2Score());
        $model->setResult($matchCreatable->getResult());
        $model->setViewers($matchCreatable->getViewers());
        $model->setAnnotation($matchCreatable->getAnnotation());

        $seasonTeam1 = $matchCreatable->getSeasonTeam1Id()
            ? $this->seasonTeamRepository->findById($matchCreatable->getSeasonTeam1Id())
            : null;
        if (!$seasonTeam1 && $matchCreatable->getSeasonTeam1Id()) {
            throw new RelatedEntityNotFoundException(
                'Season team 1 not found.'
            );
        }

        $seasonTeam2 = $matchCreatable->getSeasonTeam2Id()
            ? $this->seasonTeamRepository->findById($matchCreatable->getSeasonTeam2Id())
            : null;
        if (!$seasonTeam2 && $matchCreatable->getSeasonTeam2Id()) {
            throw new RelatedEntityNotFoundException(
                'Season team 2 not found.'
            );
        }

        $roundCreatable = $this->roundRepository->findById($matchCreatable->getRoundId());
        if (!$roundCreatable) {
            throw new RelatedEntityNotFoundException(
                'Round not found.'
            );
        }

        $model->setSeasonTeam1($seasonTeam1);
        $model->setSeasonTeam2($seasonTeam2);
        $model->setRound($roundCreatable);

        return $model;
    }
}
