<?php

namespace App\Common\Repository;

interface FindableByIdInterface
{
    public function findById(string|int $id): ?object;
}
