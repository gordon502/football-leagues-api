<?php

namespace App\Modules\User\Repository;

use App\Common\HttpQuery\Filter\HttpQueryFilter;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;

interface UserRepositoryInterface extends FindableByIdInterface
{
    public function create(UserCreatableInterface $userCreatable): UserInterface;

    public function findById(string $id): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;

    /**
     * @param array<HttpQueryFilter> $filters
     * @return array<UserInterface>
     */
    public function findByHttpQueryFilters(array $filters): array;
}
