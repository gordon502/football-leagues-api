<?php

namespace App\Modules\Game\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Game\Model\MongoDB\Game;
use App\Modules\Game\Repository\GameRepositoryInterface;
use App\Modules\Game\Factory\GameFactoryInterface;
use App\Modules\Game\Model\GameCreatableInterface;
use App\Modules\Game\Model\GameInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class GameRepository extends DocumentRepository implements GameRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly GameFactoryInterface $gameFactory;

    public function __construct(DocumentManager $dm, GameFactoryInterface $gameFactory)
    {
        $this->gameFactory = $gameFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Game::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(object $object): GameInterface
    {
        if (!$object instanceof GameCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . GameCreatableInterface::class
            );
        }

        $game = $this->gameFactory->create(
            $object,
            Game::class
        );

        $this->getDocumentManager()->persist($game);
        $this->getDocumentManager()->flush();

        return $game;
    }

    public function findById(string $id): ?GameInterface
    {
        return $this->find($id);
    }
}
