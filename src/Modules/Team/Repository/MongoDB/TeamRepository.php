<?php

namespace App\Modules\Team\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Team\Factory\TeamFactoryInterface;
use App\Modules\Team\Model\MongoDB\Team;
use App\Modules\Team\Model\TeamCreatableInterface;
use App\Modules\Team\Model\TeamInterface;
use App\Modules\Team\Repository\TeamRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class TeamRepository extends DocumentRepository implements TeamRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly TeamFactoryInterface $teamFactory;

    public function __construct(DocumentManager $dm, TeamFactoryInterface $teamFactory)
    {
        $this->teamFactory = $teamFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Team::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        TeamCreatableInterface $teamCreatable
    ): TeamInterface {
        /** @var Team $team */
        $team = $this->teamFactory->create(
            $teamCreatable,
            Team::class
        );

        $this->getDocumentManager()->persist($team);
        $this->getDocumentManager()->flush();

        return $team;
    }

    public function findById(string $id): ?TeamInterface
    {
        $team = $this->find($id);

        if ($team !== null) {
            $this->getDocumentManager()->refresh($team);
        }

        return $team;
    }
}
