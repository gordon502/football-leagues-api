<?php

namespace App\Modules\OrganizationalUnit\Factory;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitCreatableInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use InvalidArgumentException;
use ReflectionClass;

readonly class OrganizationalUnitFactory implements OrganizationalUnitFactoryInterface
{
    public function create(
        OrganizationalUnitCreatableInterface $userCreatable,
        string $modelClass
    ): OrganizationalUnitInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(OrganizationalUnitInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . OrganizationalUnitInterface::class
            );
        }

        /** @var OrganizationalUnitInterface $model */
        $model = new $modelClass();

        $model->setName($userCreatable->getName());
        $model->setCountry($userCreatable->getCountry());
        $model->setAddress($userCreatable->getAddress());
        $model->setCity($userCreatable->getCity());
        $model->setPostalCode($userCreatable->getPostalCode());
        $model->setPhone($userCreatable->getPhone());

        return $model;
    }
}
