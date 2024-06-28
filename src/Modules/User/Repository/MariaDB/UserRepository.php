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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly UserFactoryInterface $userFactory;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ManagerRegistry $registry,
        UserFactoryInterface $userFactory,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->userFactory = $userFactory;
        $this->passwordHasher = $passwordHasher;

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
        return $this->find($id);
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
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
