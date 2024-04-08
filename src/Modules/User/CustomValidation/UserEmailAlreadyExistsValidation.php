<?php

namespace App\Modules\User\CustomValidation;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Modules\User\Exception\UserEmailIsAlreadyTakenException;
use App\Modules\User\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class UserEmailAlreadyExistsValidation implements CustomValidationInterface
{
    public function __construct(
        #[Autowire(service: 'user_repository')]
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function validate($value): void
    {
        $user = $this->userRepository->findByEmail($value);

        if ($user) {
            throw new UserEmailIsAlreadyTakenException();
        }
    }
}
