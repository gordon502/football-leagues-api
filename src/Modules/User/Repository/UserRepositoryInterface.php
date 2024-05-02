<?php

namespace App\Modules\User\Repository;

use App\Common\HttpQuery\HttpQuery;
use App\Common\Pagination\PaginatedQueryResultInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;

interface UserRepositoryInterface extends FindableByIdInterface
{
    public function create(UserCreatableInterface $userCreatable): UserInterface;

    public function findById(string $id): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;

    public function findByHttpQuery(HttpQuery $query): PaginatedQueryResultInterface;
}
