<?php

namespace App\Common\HttpQuery;

use App\Common\HttpQuery\Filter\HttpQueryFilter;
use App\Common\HttpQuery\Sort\HttpQuerySort;

/**
 * @property array<HttpQueryFilter> $filters
 * @property array<HttpQuerySort> $sort
 */
readonly class HttpQuery
{
    public function __construct(
        public array $filters,
        public array $sort,
    ) {
    }
}
