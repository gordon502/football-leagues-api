<?php

namespace App\Modules\User\CustomValidation;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Modules\User\Exception\UserEmailIsAlreadyTakenException;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserEmailAlreadyExistsValidation implements CustomValidationInterface
{
    public function __construct(
        #[Autowire(service: 'user_repository')]
        private UserRepositoryInterface $userRepository,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function validate($value): void
    {
        /** @var UserInterface $loggedUser */
        $loggedUser = $this->tokenStorage->getToken()?->getUser();

        if ($loggedUser && $loggedUser->getEmail() === $value) {
            return;
        }

        $user = $this->userRepository->findByEmail($value);

        if ($user) {
            throw new UserEmailIsAlreadyTakenException();
        }
    }
}
