<?php

namespace App\Common\HttpQuery\Sort;

interface HttpQuerySortParserInterface
{
    /**
     * @param string $sortQuery
     * @param string $testedInterface
     * @return array<HttpQuerySort>
     *
     * @throws HttpQuerySortParserException If the query string is not in the right format.
     */
    public function parse(string $sortQuery, string $testedInterface): array;
}
