<?php

namespace App\Modules\GameEvent\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\GameEvent\Factory\GameEventFactoryInterface;
use App\Modules\GameEvent\Model\GameEventCreatableInterface;
use App\Modules\GameEvent\Model\GameEventInterface;
use App\Modules\GameEvent\Model\MongoDB\GameEvent;
use App\Modules\GameEvent\Repository\GameEventRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class GameEventRepository extends DocumentRepository implements GameEventRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly GameEventFactoryInterface $gameEventFactory;

    public function __construct(DocumentManager $dm, GameEventFactoryInterface $gameEventFactory)
    {
        $this->gameEventFactory = $gameEventFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(GameEvent::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(
        GameEventCreatableInterface $gameEventCreatable
    ): GameEventInterface {
        /** @var GameEventInterface $gameEvent */
        $gameEvent = $this->gameEventFactory->create(
            $gameEventCreatable,
            GameEvent::class
        );

        $this->getDocumentManager()->persist($gameEvent);
        $this->getDocumentManager()->flush();

        return $gameEvent;
    }

    public function findById(string $id): ?GameEventInterface
    {
        return $this->find($id);
    }
}
