<?php

namespace App\Modules\Round\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Round\Factory\RoundFactoryInterface;
use App\Modules\Round\Model\MariaDB\Round;
use App\Modules\Round\Model\RoundCreatableInterface;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Round\Repository\RoundRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoundRepository extends ServiceEntityRepository implements RoundRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly RoundFactoryInterface $roundFactory;

    public function __construct(
        ManagerRegistry $registry,
        RoundFactoryInterface $roundFactory
    ) {
        $this->roundFactory = $roundFactory;

        parent::__construct($registry, Round::class);
    }

    public function create(
        RoundCreatableInterface $roundCreatable
    ): RoundInterface {
        /** @var Round $round */
        $round = $this->roundFactory->create(
            $roundCreatable,
            Round::class
        );

        $em = $this->getEntityManager();
        $em->persist($round);
        $em->flush();

        return $round;
    }

    public function findById(string $id): ?RoundInterface
    {
        $round = $this->findOneBy(['id' => $id]);

        if ($round !== null) {
            $this->getEntityManager()->refresh($round);
        }

        return $round;
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
