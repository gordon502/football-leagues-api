<?php

namespace App\Common\Repository;

interface FindableByIdInterface
{
    public function findById(string $id): ?object;
}
