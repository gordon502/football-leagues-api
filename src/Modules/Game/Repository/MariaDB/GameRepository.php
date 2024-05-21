<?php

namespace App\Modules\Game\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Game\Model\MariaDB\Game;
use App\Modules\Game\Repository\GameRepositoryInterface;
use App\Modules\Game\Factory\GameFactoryInterface;
use App\Modules\Game\Model\GameCreatableInterface;
use App\Modules\Game\Model\GameInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly GameFactoryInterface $gameFactory;

    public function __construct(
        ManagerRegistry $registry,
        GameFactoryInterface $gameFactory
    ) {
        $this->gameFactory = $gameFactory;

        parent::__construct($registry, Game::class);
    }

    public function create(
        GameCreatableInterface $gameCreatable
    ): GameInterface {
        /** @var Game $game */
        $game = $this->gameFactory->create(
            $gameCreatable,
            Game::class
        );

        $em = $this->getEntityManager();
        $em->persist($game);
        $em->flush();

        return $game;
    }

    public function findById(string $id): ?GameInterface
    {
        $game = $this->findOneBy(['id' => $id]);

        if ($game !== null) {
            $this->getEntityManager()->refresh($game);
        }

        return $game;
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
}
