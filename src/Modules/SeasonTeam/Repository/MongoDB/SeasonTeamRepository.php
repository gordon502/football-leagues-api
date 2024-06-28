<?php

namespace App\Modules\SeasonTeam\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\SeasonTeam\Factory\SeasonTeamFactoryInterface;
use App\Modules\SeasonTeam\Model\MongoDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamCreatableInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SeasonTeamRepository extends DocumentRepository implements SeasonTeamRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly SeasonTeamFactoryInterface $seasonTeamFactory;

    public function __construct(DocumentManager $dm, SeasonTeamFactoryInterface $seasonTeamFactory)
    {
        $this->seasonTeamFactory = $seasonTeamFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(SeasonTeam::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        SeasonTeamCreatableInterface $seasonCreatable
    ): SeasonTeamInterface {
        /** @var SeasonTeam $seasonTeam */
        $seasonTeam = $this->seasonTeamFactory->create(
            $seasonCreatable,
            SeasonTeam::class
        );

        $this->getDocumentManager()->persist($seasonTeam);
        $this->getDocumentManager()->flush();

        return $seasonTeam;
    }

    public function findById(string $id): ?SeasonTeamInterface
    {
        return $this->find($id);
    }
}
