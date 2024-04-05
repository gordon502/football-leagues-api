<?php

namespace App\Modules\User\Repository\MongoDB;

use App\Modules\User\Model\MongoDB\User;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class UserRepository extends DocumentRepository implements UserRepositoryInterface
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(User::class);

        parent::__construct($dm, $uow, $classMetadata);
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
