<?php

namespace App\Modules\OrganizationalUnit\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\OrganizationalUnit\Factory\OrganizationalUnitFactoryInterface;
use App\Modules\OrganizationalUnit\Model\MariaDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitCreatableInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\OrganizationalUnit\Repository\OrganizationalUnitRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationalUnitRepository extends ServiceEntityRepository implements OrganizationalUnitRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly OrganizationalUnitFactoryInterface $organizationalUnitFactory;

    public function __construct(
        ManagerRegistry $registry,
        OrganizationalUnitFactoryInterface $organizationalUnitFactory
    ) {
        $this->organizationalUnitFactory = $organizationalUnitFactory;

        parent::__construct($registry, OrganizationalUnit::class);
    }

    public function create(object $object): OrganizationalUnitInterface
    {
        if (!$object instanceof OrganizationalUnitCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . OrganizationalUnitCreatableInterface::class
            );
        }

        $organizationalUnit = $this->organizationalUnitFactory->create(
            $object,
            OrganizationalUnit::class
        );

        $em = $this->getEntityManager();
        $em->persist($organizationalUnit);
        $em->flush();

        return $organizationalUnit;
    }

    public function findById(string $id): ?OrganizationalUnitInterface
    {
        return $this->find($id);
    }

    public function delete(string $id): void
    {
        $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
