<?php

namespace App\Common\Repository;

interface DeletableByIdInterface
{
    public function delete(string $id): void;
}
