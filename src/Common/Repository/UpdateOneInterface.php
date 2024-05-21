<?php

namespace App\Common\Repository;

interface UpdateOneInterface
{
    public function updateOne(string|object $idOrObject, object $updatable, bool $transactional = false): object|false;

    public function flushUpdateOne(): void;
}
