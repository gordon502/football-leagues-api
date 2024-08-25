<?php

namespace App\Modules\Season\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
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
    use DeleteTrait;

    private readonly SeasonFactoryInterface $seasonFactory;

    public function __construct(DocumentManager $dm, SeasonFactoryInterface $seasonFactory)
    {
        $this->seasonFactory = $seasonFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Season::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(object $object): SeasonInterface
    {
        if (!$object instanceof SeasonCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . SeasonCreatableInterface::class
            );
        }

        $season = $this->seasonFactory->create(
            $object,
            Season::class
        );

        $this->getDocumentManager()->persist($season);
        $this->getDocumentManager()->flush();

        return $season;
    }

    public function findById(string $id): ?SeasonInterface
    {
        return $this->find($id);
    }
}
