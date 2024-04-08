<?php

namespace App\Modules\User\Factory;

use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Role\UserRole;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }


    public function create(UserCreatableInterface $userCreatable, string $modelClass): UserInterface
    {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(UserInterface::class)) {
            throw new InvalidArgumentException('Model class must implement ' . UserInterface::class);
        }

        /** @var UserInterface $model */
        $model = new $modelClass();

        $model->setEmail($userCreatable->getEmail());
        $model->setName($userCreatable->getName());
        $model->setPassword($this->passwordHasher->hashPassword($model, $userCreatable->getPassword()));
        $model->setRole(UserRole::USER);
        $model->setBlocked(false);
        $model->setAvatar(null);

        return $model;
    }
}
