<?php

namespace App\Modules\Round\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Round\Factory\RoundFactoryInterface;
use App\Modules\Round\Model\MongoDB\Round;
use App\Modules\Round\Model\RoundCreatableInterface;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Round\Repository\RoundRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class RoundRepository extends DocumentRepository implements RoundRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly RoundFactoryInterface $roundFactory;

    public function __construct(DocumentManager $dm, RoundFactoryInterface $roundFactory)
    {
        $this->roundFactory = $roundFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Round::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        RoundCreatableInterface $roundCreatable
    ): RoundInterface {
        /** @var Round $round */
        $round = $this->roundFactory->create(
            $roundCreatable,
            Round::class
        );

        $this->getDocumentManager()->persist($round);
        $this->getDocumentManager()->flush();

        return $round;
    }

    public function findById(string $id): ?RoundInterface
    {
        return $this->find($id);
    }
}
