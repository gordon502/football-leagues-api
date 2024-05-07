<?php

namespace App\Modules\League\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\League\Model\LeagueCreatableInterface;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\OrganizationalUnit\Repository\OrganizationalUnitRepositoryInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class LeagueFactory implements LeagueFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'organizational_unit_repository')]
        private OrganizationalUnitRepositoryInterface $organizationalUnitRepository
    ) {
    }

    public function create(
        LeagueCreatableInterface $leagueCreatable,
        string $modelClass
    ): LeagueInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(LeagueInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . LeagueInterface::class
            );
        }

        /** @var LeagueInterface $model */
        $model = new $modelClass();

        $model->setName($leagueCreatable->getName());
        $model->setActive($leagueCreatable->isActive());
        $model->setLevel($leagueCreatable->getLevel());

        $organizationalUnit = $this->organizationalUnitRepository->findById(
            $leagueCreatable->getOrganizationalUnitId()
        );

        if (!$organizationalUnit) {
            throw new RelatedEntityNotFoundException(
                'Organizational unit not found.'
            );
        }

        $model->setOrganizationalUnit($organizationalUnit);

        return $model;
    }
}
