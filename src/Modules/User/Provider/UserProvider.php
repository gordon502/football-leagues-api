<?php

namespace App\Modules\User\Provider;

use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        #[Autowire(service: 'user_repository')]
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function refreshUser(SecurityUserInterface $user): SecurityUserInterface
    {
        $user = $this->userRepository->findByEmail($user->getUserIdentifier());

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        $reflectionClass = new ReflectionClass($class);
        return $reflectionClass->implementsInterface(UserInterface::class);
    }

    public function loadUserByIdentifier(string $identifier): SecurityUserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
