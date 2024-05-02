<?php

namespace App\Common\HttpQuery;

use App\Common\HttpQuery\Filter\HttpQueryFilterParserInterface;
use App\Common\HttpQuery\Sort\HttpQuerySortParserInterface;
use Symfony\Component\HttpFoundation\InputBag;

readonly class HttpQueryHandler implements HttpQueryHandlerInterface
{
    public function __construct(
        private HttpQueryFilterParserInterface $filterParser,
        private HttpQuerySortParserInterface $sortParser
    ) {
    }

    public function handle(InputBag $filterQuery, string $testedInterface): HttpQuery
    {
        return new HttpQuery(
            $this->filterParser->parse(
                $filterQuery->get('filter') ?? '',
                $testedInterface
            ),
            $this->sortParser->parse(
                $filterQuery->get('sort') ?? '',
                $testedInterface
            ),
        );
    }
}
