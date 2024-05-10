<?php

namespace App\Modules\Season\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\League\Repository\LeagueRepositoryInterface;
use App\Modules\Season\Model\SeasonCreatableInterface;
use App\Modules\Season\Model\SeasonInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class SeasonFactory implements SeasonFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'league_repository')]
        private LeagueRepositoryInterface $leagueRepository
    ) {
    }

    public function create(
        SeasonCreatableInterface $seasonCreatable,
        string $modelClass
    ): SeasonInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(SeasonInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . SeasonInterface::class
            );
        }

        /** @var SeasonInterface $model */
        $model = new $modelClass();

        $model->setName($seasonCreatable->getName());
        $model->setPeriod($seasonCreatable->getPeriod());
        $model->setActive($seasonCreatable->isActive());

        $league = $this->leagueRepository->findById(
            $seasonCreatable->getLeagueId()
        );

        if (!$league) {
            throw new RelatedEntityNotFoundException(
                'League not found.'
            );
        }

        $model->setLeague($league);

        return $model;
    }
}
