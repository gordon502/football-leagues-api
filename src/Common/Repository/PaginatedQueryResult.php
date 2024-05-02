<?php

namespace App\Common\Repository;

use App\Common\Pagination\PaginatedQueryResultInterface;

readonly class PaginatedQueryResult implements PaginatedQueryResultInterface
{
    public function __construct(
        public array $data,
        public int $total,
        public int $limit,
        public int $currentPage,
        public int $totalPages,
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}
