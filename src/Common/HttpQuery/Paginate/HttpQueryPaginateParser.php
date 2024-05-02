<?php

namespace App\Common\HttpQuery\Paginate;

class HttpQueryPaginateParser implements HttpQueryPaginateParserInterface
{
    // TODO: better error handling (separate exceptions for each case)
    public function parse(string $page, string $limit): HttpQueryPaginate
    {
        if (!$this->isPositiveInteger($page)) {
            throw new HttpQueryPaginateParserException();
        }

        if (!$this->isPositiveInteger($limit)) {
            throw new HttpQueryPaginateParserException();
        }

        $pageInt = (int) $page;
        $limitInt = (int) $limit;


        if ($limitInt > 50) {
            throw new HttpQueryPaginateParserException();
        }

        return new HttpQueryPaginate($pageInt, $limitInt);
    }

    private function isPositiveInteger(string $candidateNumber): bool
    {
        return preg_match('/^[1-9][0-9]*$/', $candidateNumber);
    }
}
