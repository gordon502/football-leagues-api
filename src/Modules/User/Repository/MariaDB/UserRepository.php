<?php

namespace App\Modules\User\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\User\Factory\UserFactoryInterface;
use App\Modules\User\Model\MariaDB\User;
use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly UserFactoryInterface $userFactory;

    public function __construct(
        ManagerRegistry $registry,
        UserFactoryInterface $userFactory
    ) {
        $this->userFactory = $userFactory;

        parent::__construct($registry, User::class);
    }

    public function create(UserCreatableInterface $userCreatable): UserInterface
    {
        /** @var User $user */
        $user = $this->userFactory->create($userCreatable, User::class);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function findById(string $id): ?UserInterface
    {
        $user = $this->findOneBy(['id' => $id]);

        if ($user !== null) {
            $this->getEntityManager()->refresh($user);
        }

        return $user;
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }
}
