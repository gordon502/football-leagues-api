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

    public function create(
        LeagueCreatableInterface $leagueCreatable
    ): LeagueInterface {
        /** @var League $league */
        $league = $this->leagueFactory->create(
            $leagueCreatable,
            League::class
        );

        $em = $this->getEntityManager();
        $em->persist($league);
        $em->flush();

        return $league;
    }

    public function findById(string $id): ?LeagueInterface
    {
        $league = $this->findOneBy(['id' => $id]);

        if ($league !== null) {
            $this->getEntityManager()->refresh($league);
        }

        return $league;
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
