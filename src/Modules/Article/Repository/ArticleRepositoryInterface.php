<?php

namespace App\Modules\Article\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Article\Model\ArticleInterface;

interface ArticleRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?ArticleInterface;
}
