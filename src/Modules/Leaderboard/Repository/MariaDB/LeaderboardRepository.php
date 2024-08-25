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

    public function create(object $object): LeaderboardInterface
    {
        if (!$object instanceof LeaderboardCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . LeaderboardCreatableInterface::class
            );
        }

        $leaderboard = $this->leaderboardFactory->create(
            $object,
            Leaderboard::class
        );

        $em = $this->getEntityManager();
        $em->persist($leaderboard);
        $em->flush();

        return $leaderboard;
    }

    public function findById(string $id): ?LeaderboardInterface
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

    public function findAllForSeasonTeam(SeasonTeamInterface $seasonTeam): array
    {
        return $this->findBy(['seasonTeam' => $seasonTeam]);
    }
}
