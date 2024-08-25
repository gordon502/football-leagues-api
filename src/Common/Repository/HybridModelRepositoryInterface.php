<?php

namespace App\Common\Repository;

use App\Common\HttpQuery\HttpQuery;
use App\Common\Pagination\PaginatedQueryResultInterface;

interface HybridModelRepositoryInterface
{
    public function create(object $object): object;

    public function findById(string $id): ?object;

    public function findByHttpQuery(HttpQuery $query): PaginatedQueryResultInterface;

    public function updateOne(string|object $idOrObject, object $updatable, bool $transactional = false): object|false;

    public function flushUpdateOne(): void;

    public function delete(string $id): void;
}
