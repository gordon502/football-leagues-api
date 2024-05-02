<?php

namespace App\Modules\User\Repository\MongoDB;

use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Modules\User\Factory\UserFactoryInterface;
use App\Modules\User\Model\MongoDB\User;
use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class UserRepository extends DocumentRepository implements UserRepositoryInterface
{
    use FindByHttpQueryTrait;

    private readonly UserFactoryInterface $userFactory;

    public function __construct(DocumentManager $dm, UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(User::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(UserCreatableInterface $userCreatable): UserInterface
    {
        /** @var User $user */
        $user = $this->userFactory->create($userCreatable, User::class);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $user;
    }

    public function findById(string $id): ?UserInterface
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }
}
