<?php

namespace App\Modules\Season\Repository\MongoDB;

use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Season\Factory\SeasonFactoryInterface;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonCreatableInterface;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class SeasonRepository extends DocumentRepository implements SeasonRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly SeasonFactoryInterface $seasonFactory;

    public function __construct(DocumentManager $dm, SeasonFactoryInterface $seasonFactory)
    {
        $this->seasonFactory = $seasonFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Season::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        SeasonCreatableInterface $seasonCreatable
    ): SeasonInterface {
        /** @var Season $season */
        $season = $this->seasonFactory->create(
            $seasonCreatable,
            Season::class
        );

        $this->getDocumentManager()->persist($season);
        $this->getDocumentManager()->flush();

        return $season;
    }

    public function findById(string $id): ?SeasonInterface
    {
        $season = $this->find($id);

        if ($season !== null) {
            $this->getDocumentManager()->refresh($season);
        }

        return $season;
    }

    public function delete(string $id): void
    {
        $this->createQueryBuilder()
            ->remove()
            ->field('id')->equals($id)
            ->getQuery()
            ->execute();
    }
}
