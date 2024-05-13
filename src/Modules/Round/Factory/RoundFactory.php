<?php

namespace App\Modules\Round\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Round\Model\RoundCreatableInterface;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use DateTime;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class RoundFactory implements RoundFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'season_repository')]
        private SeasonRepositoryInterface $seasonRepository,
    ) {
    }

    public function create(
        RoundCreatableInterface $roundCreatable,
        string $modelClass
    ): RoundInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(RoundInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . RoundInterface::class
            );
        }

        /** @var RoundInterface $model */
        $model = new $modelClass();

        $model->setNumber($roundCreatable->getNumber());
        $model->setStandardStartDate(DateTime::createFromFormat('Y-m-d', $roundCreatable->getStandardStartDate()));
        $model->setStandardEndDate(DateTime::createFromFormat('Y-m-d', $roundCreatable->getStandardEndDate()));

        $season = $this->seasonRepository->findById($roundCreatable->getSeasonId());
        if (!$season) {
            throw new RelatedEntityNotFoundException(
                'Season not found.'
            );
        }
        $model->setSeason($season);

        return $model;
    }
}
