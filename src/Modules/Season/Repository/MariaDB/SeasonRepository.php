<?php

namespace App\Modules\Season\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Season\Factory\SeasonFactoryInterface;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonCreatableInterface;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeasonRepository extends ServiceEntityRepository implements SeasonRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly SeasonFactoryInterface $seasonFactory;

    public function __construct(
        ManagerRegistry $registry,
        SeasonFactoryInterface $seasonFactory
    ) {
        $this->seasonFactory = $seasonFactory;

        parent::__construct($registry, Season::class);
    }

    public function create(
        SeasonCreatableInterface $seasonCreatable
    ): SeasonInterface {
        /** @var Season $season */
        $season = $this->seasonFactory->create(
            $seasonCreatable,
            Season::class
        );

        $em = $this->getEntityManager();
        $em->persist($season);
        $em->flush();

        return $season;
    }

    public function findById(string $id): ?SeasonInterface
    {
        $season = $this->findOneBy(['id' => $id]);

        if ($season !== null) {
            $this->getEntityManager()->refresh($season);
        }

        return $season;
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
