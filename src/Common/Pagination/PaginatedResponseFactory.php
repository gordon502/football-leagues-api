<?php

namespace App\Common\Pagination;

use App\Common\Serialization\RoleBasedSerializerInterface;

readonly class PaginatedResponseFactory implements PaginatedResponseFactoryInterface
{
    public function __construct(
        private RoleBasedSerializerInterface $serializer,
    ) {
    }

    public function fromPaginatedQueryResultInterface(
        PaginatedQueryResultInterface $paginatedQueryResult,
        string $getDtoClass,
    ): PaginatedResponse {
        return new PaginatedResponse(
            data: array_map(
                fn($entity) => $this->serializer->normalize(new $getDtoClass($entity)),
                $paginatedQueryResult->getData(),
            ),
            pagination: new Pagination(
                total: $paginatedQueryResult->getTotal(),
                limit: $paginatedQueryResult->getLimit(),
                currentPage: $paginatedQueryResult->getCurrentPage(),
                totalPages: $paginatedQueryResult->getTotalPages(),
            ),
        );
    }
}
