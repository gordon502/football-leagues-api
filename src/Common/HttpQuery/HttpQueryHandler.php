<?php

namespace App\Common\HttpQuery;

use App\Common\HttpQuery\Filter\HttpQueryFilterParserInterface;
use App\Common\HttpQuery\Paginate\HttpQueryPaginateParserInterface;
use App\Common\HttpQuery\Sort\HttpQuerySortParserInterface;
use Symfony\Component\HttpFoundation\InputBag;

readonly class HttpQueryHandler implements HttpQueryHandlerInterface
{
    public function __construct(
        private HttpQueryFilterParserInterface $filterParser,
        private HttpQuerySortParserInterface $sortParser,
        private HttpQueryPaginateParserInterface $paginateParser,
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
            $this->paginateParser->parse(
                page: $filterQuery->get('page') ?? '1',
                limit: $filterQuery->get('limit') ?? '20'
            )
        );
    }
}
