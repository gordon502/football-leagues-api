<?php

namespace App\Common\Repository\MariaDB;

use App\Common\HttpQuery\Filter\HttpQueryFilterOperatorEnum;
use App\Common\HttpQuery\HttpQuery;
use App\Common\Pagination\PaginatedQueryResultInterface;
use App\Common\Pagination\PaginationOutOfBoundException;
use App\Common\Repository\PaginatedQueryResult;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 */
trait FindByHttpQueryTrait
{
    public function findByHttpQuery(HttpQuery $query): PaginatedQueryResultInterface
    {
        $qb = $this->createQueryBuilder('filter');

        foreach ($query->filters as $filter) {
            if (
                $filter->operator === HttpQueryFilterOperatorEnum::MARIA_DB_IS_NULL
                || $filter->operator === HttpQueryFilterOperatorEnum::MARIA_DB_IS_NOT_NULL
            ) {
                $qb->andWhere("filter.{$filter->field} {$filter->operator->value}");

                continue;
            }

            if ($filter->operator === HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_TRUE) {
                $qb->andWhere(HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_TRUE->value);

                continue;
            }

            if ($filter->operator === HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_FALSE) {
                $qb->andWhere(HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_FALSE->value);

                continue;
            }

            if ($filter->isFieldReference) {
                $qb
                    ->leftJoin("filter.{$filter->field}", $filter->field)
                    ->andWhere("{$filter->field}.id {$filter->operator->value} :{$filter->field}")
                    ->setParameter($filter->field, $filter->value);

                continue;
            }

            $qb
                ->andWhere("filter.{$filter->field} {$filter->operator->value} :{$filter->field}")
                ->setParameter($filter->field, $filter->value);
        }

        foreach ($query->sort as $sort) {
            if ($sort->isFieldReference) {
                $qb
                    ->leftJoin("filter.{$sort->field}", $sort->field)
                    ->addOrderBy("{$sort->field}.id", $sort->direction);

                continue;
            }

            $qb
                ->addOrderBy("filter.{$sort->field}", $sort->direction);
        }

        $totalCount = (clone $qb)->select('COUNT(filter)')->getQuery()->getSingleScalarResult();

        $qb
            ->setFirstResult(($query->paginate->page - 1) * $query->paginate->limit)
            ->setMaxResults($query->paginate->limit);

        $data = $qb->getQuery()->getResult();

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
}
