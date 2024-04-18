<?php

namespace App\Common\HttpQuery;

use Symfony\Component\HttpFoundation\InputBag;

readonly class HttpQueryHandler implements HttpQueryHandlerInterface
{
    public function __construct(
        private HttpQueryFilterParserInterface $filterParser,
    ) {
    }

    public function handle(InputBag $filterQuery, string $testedInterface): array
    {
        return [
            'filters' => $this->filterParser->parse(
                $filterQuery->get('filter') ?? '',
                $testedInterface
            ),
        ];
    }
}
