<?php

namespace App\Common\Repository;

interface UpdateOneInterface
{
    public function updateOne(string $id, object $updatable, bool $transactional = false): bool;

    public function commitUpdateOne(): void;

    public function rollBackUpdateOne(): void;
}
