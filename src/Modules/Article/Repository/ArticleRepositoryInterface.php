<?php

namespace App\Modules\Article\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Common\Repository\UpdateOneInterface;
use App\Modules\Article\Model\ArticleInterface;

interface ArticleRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface, UpdateOneInterface
{
    public function findById(string $id): ?ArticleInterface;
}
