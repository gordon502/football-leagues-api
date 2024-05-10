<?php

namespace App\Modules\Team\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\OrganizationalUnit\Repository\OrganizationalUnitRepositoryInterface;
use App\Modules\Team\Model\TeamCreatableInterface;
use App\Modules\Team\Model\TeamInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class TeamFactory implements TeamFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'organizational_unit_repository')]
        private OrganizationalUnitRepositoryInterface $organizationalUnitRepository
    ) {
    }

    public function create(
        TeamCreatableInterface $teamCreatable,
        string $modelClass
    ): TeamInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(TeamInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . TeamInterface::class
            );
        }

        /** @var TeamInterface $model */
        $model = new $modelClass();

        $model->setName($teamCreatable->getName());
        $model->setYearEstablished($teamCreatable->getYearEstablished());
        $model->setColors($teamCreatable->getColors());
        $model->setCountry($teamCreatable->getCountry());
        $model->setAddress($teamCreatable->getAddress());
        $model->setCity($teamCreatable->getCity());
        $model->setPostalCode($teamCreatable->getPostalCode());
        $model->setSite($teamCreatable->getSite());
        $model->setStadium($teamCreatable->getStadium());

        $organizationalUnit = $this->organizationalUnitRepository->findById(
            $teamCreatable->getOrganizationalUnitId()
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
