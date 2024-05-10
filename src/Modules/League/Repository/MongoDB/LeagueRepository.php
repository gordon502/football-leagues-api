<?php

namespace App\Modules\League\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\League\Factory\LeagueFactoryInterface;
use App\Modules\League\Model\LeagueCreatableInterface;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MongoDB\League;
use App\Modules\League\Repository\LeagueRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class LeagueRepository extends DocumentRepository implements LeagueRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly LeagueFactoryInterface $leagueFactory;

    public function __construct(DocumentManager $dm, LeagueFactoryInterface $leagueFactory)
    {
        $this->leagueFactory = $leagueFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(League::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        LeagueCreatableInterface $leagueCreatable
    ): LeagueInterface {
        /** @var League $league */
        $league = $this->leagueFactory->create(
            $leagueCreatable,
            League::class
        );

        $this->getDocumentManager()->persist($league);
        $this->getDocumentManager()->flush();

        return $league;
    }

    public function findById(string $id): ?LeagueInterface
    {
        $league = $this->find($id);

        if ($league !== null) {
            $this->getDocumentManager()->refresh($league);
        }

        return $league;
    }
}
