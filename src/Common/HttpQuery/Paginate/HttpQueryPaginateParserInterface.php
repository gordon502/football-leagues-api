<?php

namespace App\Common\HttpQuery\Paginate;

use App\Common\HttpQuery\Filter\HttpQueryFilterParserException;

interface HttpQueryPaginateParserInterface
{
    /**
     * @throws HttpQueryFilterParserException If the query string is not in the right format.
     */
    public function parse(string $page, string $limit): HttpQueryPaginate;
}
