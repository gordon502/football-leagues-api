<?php

namespace App\Modules\Leaderboard\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Leaderboard\Factory\LeaderboardFactoryInterface;
use App\Modules\Leaderboard\Model\MongoDB\Leaderboard;
use App\Modules\Leaderboard\Model\LeaderboardCreatableInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Repository\LeaderboardRepositoryInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class LeaderboardRepository extends DocumentRepository implements LeaderboardRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly LeaderboardFactoryInterface $leaderboardFactory;

    public function __construct(DocumentManager $dm, LeaderboardFactoryInterface $leaderboardFactory)
    {
        $this->leaderboardFactory = $leaderboardFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Leaderboard::class);

        parent::__construct($dm, $uow, $classMetadata);
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

        $this->getDocumentManager()->persist($leaderboard);
        $this->getDocumentManager()->flush();

        return $leaderboard;
    }

    public function findById(string $id): ?LeaderboardInterface
    {
        return $this->find($id);
    }

    public function findAllForSeasonTeam(SeasonTeamInterface $seasonTeam): array
    {
        return $this->findBy(['seasonTeam' => $seasonTeam]);
    }
}
