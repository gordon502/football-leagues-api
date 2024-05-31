<?php

namespace App\Common\Repository\MongoDB;

use App\Common\HttpQuery\HttpQuery;
use App\Common\Pagination\PaginatedQueryResultInterface;
use App\Common\Pagination\PaginationOutOfBoundException;
use App\Common\Repository\PaginatedQueryResult;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use MongoDB\BSON\Regex;

/**
 * @method QueryBuilder createQueryBuilder()
 */
trait FindByHttpQueryTrait
{
    public function findByHttpQuery(HttpQuery $query): PaginatedQueryResultInterface
    {
        $qb = $this->createQueryBuilder();

        foreach ($query->filters as $filter) {
            if ($filter->operator === '$regex') {
                $qb
                    ->field($filter->field)
                    ->equals($this->sqlLikeToRegex($filter->value));
                continue;
            }

            $qb
                ->field($filter->field)->{$filter->operator}($filter->value);
        }

        foreach ($query->sort as $sort) {
            $qb
                ->sort($sort->field, $sort->direction === 'asc' ? 1 : -1);
        }

        $totalCount = (clone $qb)->count()->getQuery()->execute();

        $qb
            ->skip(($query->paginate->page - 1) * $query->paginate->limit)
            ->limit($query->paginate->limit);

        $data = $qb->getQuery()->execute()->toArray();

        $totalPages = (int) ceil($totalCount / $query->paginate->limit);

        if ($query->paginate->page > $totalPages && $query->paginate->page !== 1) {
            throw new PaginationOutOfBoundException();
        }

        return new PaginatedQueryResult(
            data: $data,
            total: $totalCount,
            limit: $query->paginate->limit,
            currentPage: $query->paginate->page,
            totalPages: $totalPages,
        );
    }

    private function sqlLikeToRegex(string $sqlLike): Regex
    {
        $regex = str_replace('%', '.*', $sqlLike);
        return new Regex("^$regex$", 'i');
    }
}
