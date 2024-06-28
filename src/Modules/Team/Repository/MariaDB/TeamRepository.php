<?php

namespace App\Modules\Team\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Team\Factory\TeamFactoryInterface;
use App\Modules\Team\Model\MariaDB\Team;
use App\Modules\Team\Model\TeamCreatableInterface;
use App\Modules\Team\Model\TeamInterface;
use App\Modules\Team\Repository\TeamRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository implements TeamRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly TeamFactoryInterface $teamFactory;

    public function __construct(
        ManagerRegistry $registry,
        TeamFactoryInterface $teamFactory
    ) {
        $this->teamFactory = $teamFactory;

        parent::__construct($registry, Team::class);
    }

    public function create(
        TeamCreatableInterface $teamCreatable
    ): TeamInterface {
        /** @var Team $team */
        $team = $this->teamFactory->create(
            $teamCreatable,
            Team::class
        );

        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();

        return $team;
    }

    public function findById(string $id): ?TeamInterface
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
