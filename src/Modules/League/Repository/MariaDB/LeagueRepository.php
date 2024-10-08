<?php

namespace App\Modules\League\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\League\Factory\LeagueFactoryInterface;
use App\Modules\League\Model\LeagueCreatableInterface;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MariaDB\League;
use App\Modules\League\Repository\LeagueRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly LeagueFactoryInterface $leagueFactory;

    public function __construct(
        ManagerRegistry $registry,
        LeagueFactoryInterface $leagueFactory
    ) {
        $this->leagueFactory = $leagueFactory;

        parent::__construct($registry, League::class);
    }

    public function create(object $object): LeagueInterface
    {
        if (!$object instanceof LeagueCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . LeagueCreatableInterface::class
            );
        }

        $league = $this->leagueFactory->create(
            $object,
            League::class
        );

        $em = $this->getEntityManager();
        $em->persist($league);
        $em->flush();

        return $league;
    }

    public function findById(string $id): ?LeagueInterface
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
