<?php

namespace App\Common\Pagination;

interface PaginatedQueryResultInterface
{
    public function getData(): array;

    public function getTotal(): int;

    public function getLimit(): int;

    public function getCurrentPage(): int;

    public function getTotalPages(): int;
}
