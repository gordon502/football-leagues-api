<?php

namespace App\Common\HttpQuery\Paginate;

readonly class HttpQueryPaginate
{
    public function __construct(
        public int $page,
        public int $limit,
    ) {
    }
}
