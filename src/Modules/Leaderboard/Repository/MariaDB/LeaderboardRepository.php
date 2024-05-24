<?php

namespace App\Modules\Leaderboard\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Leaderboard\Factory\LeaderboardFactoryInterface;
use App\Modules\Leaderboard\Model\MariaDB\Leaderboard;
use App\Modules\Leaderboard\Model\LeaderboardCreatableInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Repository\LeaderboardRepositoryInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LeaderboardRepository extends ServiceEntityRepository implements LeaderboardRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly LeaderboardFactoryInterface $leaderboardFactory;

    public function __construct(
        ManagerRegistry $registry,
        LeaderboardFactoryInterface $leaderboardFactory
    ) {
        $this->leaderboardFactory = $leaderboardFactory;

        parent::__construct($registry, Leaderboard::class);
    }

    public function create(
        LeaderboardCreatableInterface $leaderboardCreatable
    ): LeaderboardInterface {
        /** @var Leaderboard $leaderboard */
        $leaderboard = $this->leaderboardFactory->create(
            $leaderboardCreatable,
            Leaderboard::class
        );

        $em = $this->getEntityManager();
        $em->persist($leaderboard);
        $em->flush();

        return $leaderboard;
    }

    public function findById(string $id): ?LeaderboardInterface
    {
        $leaderboard = $this->findOneBy(['id' => $id]);

        if ($leaderboard !== null) {
            $this->getEntityManager()->refresh($leaderboard);
        }

        return $leaderboard;
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

    public function findAllForSeasonTeam(SeasonTeamInterface $seasonTeam): array
    {
        return $this->findBy(['seasonTeam' => $seasonTeam]);
    }
}
