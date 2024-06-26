<?php

namespace App\Common\HttpQuery\Filter;

interface HttpQueryFilterParserInterface
{
    /**
     * @param string $filterQuery
     * @param string $testedInterface
     * @return array<HttpQueryFilter>
     *
     * @throws HttpQueryFilterParserException If the query string is not in the right format.
     */
    public function parse(string $filterQuery, string $testedInterface): array;
}
