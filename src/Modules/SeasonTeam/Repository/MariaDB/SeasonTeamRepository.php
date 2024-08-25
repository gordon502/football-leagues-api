<?php

namespace App\Modules\SeasonTeam\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\SeasonTeam\Factory\SeasonTeamFactoryInterface;
use App\Modules\SeasonTeam\Model\MariaDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamCreatableInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SeasonTeamRepository extends ServiceEntityRepository implements SeasonTeamRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly SeasonTeamFactoryInterface $seasonTeamFactory;

    public function __construct(
        ManagerRegistry $registry,
        SeasonTeamFactoryInterface $seasonTeamFactory
    ) {
        $this->seasonTeamFactory = $seasonTeamFactory;

        parent::__construct($registry, SeasonTeam::class);
    }

    public function create(object $object): SeasonTeamInterface
    {
        if (!$object instanceof SeasonTeamCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . SeasonTeamCreatableInterface::class
            );
        }

        $seasonTeam = $this->seasonTeamFactory->create(
            $object,
            SeasonTeam::class
        );

        $em = $this->getEntityManager();
        $em->persist($seasonTeam);
        $em->flush();

        return $seasonTeam;
    }

    public function findById(string $id): ?SeasonTeamInterface
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
