<?php

namespace App\Common\Pagination;

interface PaginatedResponseFactoryInterface
{
    public function fromPaginatedQueryResultInterface(
        PaginatedQueryResultInterface $paginatedQueryResult,
        string $getDtoClass,
    ): PaginatedResponse;
}
