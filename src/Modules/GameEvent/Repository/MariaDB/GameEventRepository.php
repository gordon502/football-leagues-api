<?php

namespace App\Modules\GameEvent\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\GameEvent\Factory\GameEventFactoryInterface;
use App\Modules\GameEvent\Model\GameEventCreatableInterface;
use App\Modules\GameEvent\Model\GameEventInterface;
use App\Modules\GameEvent\Model\MariaDB\GameEvent;
use App\Modules\GameEvent\Repository\GameEventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameEventRepository extends ServiceEntityRepository implements GameEventRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly GameEventFactoryInterface $gameEventFactory;

    public function __construct(
        ManagerRegistry $registry,
        GameEventFactoryInterface $gameEventFactory
    ) {
        $this->gameEventFactory = $gameEventFactory;

        parent::__construct($registry, GameEvent::class);
    }

    public function create(
        GameEventCreatableInterface $gameEventCreatable
    ): GameEventInterface {
        /** @var GameEvent $gameEvent */
        $gameEvent = $this->gameEventFactory->create(
            $gameEventCreatable,
            GameEvent::class
        );

        $em = $this->getEntityManager();
        $em->persist($gameEvent);
        $em->flush();

        return $gameEvent;
    }

    public function findById(string $id): ?GameEventInterface
    {
        return $this->find($id);
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
