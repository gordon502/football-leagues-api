<?php

namespace App\Modules\Article\Factory;

use App\Modules\Article\Model\ArticleCreatableInterface;
use App\Modules\Article\Model\ArticleInterface;

interface ArticleFactoryInterface
{
    public function create(
        ArticleCreatableInterface $articleCreatable,
        string $modelClass
    ): ArticleInterface;
}
