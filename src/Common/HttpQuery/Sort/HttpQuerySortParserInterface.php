<?php

namespace App\Common\HttpQuery\Sort;

use App\Common\HttpQuery\Exception\HttpQueryFilterParserException;

interface HttpQuerySortParserInterface
{
    /**
     * @param string $sortQuery
     * @param string $testedInterface
     * @return array<HttpQuerySort>
     *
     * @throws HttpQueryFilterParserException If the query string is not in the right format.
     */
    public function parse(string $sortQuery, string $testedInterface): array;
}
