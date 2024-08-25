<?php

namespace App\Modules\User\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\User\Factory\UserFactoryInterface;
use App\Modules\User\Model\MongoDB\User;
use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends DocumentRepository implements UserRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly UserFactoryInterface $userFactory;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        DocumentManager $dm,
        UserFactoryInterface $userFactory,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->userFactory = $userFactory;
        $this->passwordHasher = $passwordHasher;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(User::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(object $object): UserInterface
    {
        if (!$object instanceof UserCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . UserCreatableInterface::class
            );
        }

        $user = $this->userFactory->create($object, User::class);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

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
}
