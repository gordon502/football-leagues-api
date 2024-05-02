<?php

namespace App\Common\Repository\MariaDB;

use App\Common\HttpQuery\HttpQuery;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 */
trait FindByHttpQueryTrait
{
    public function findByHttpQuery(HttpQuery $query): array
    {
        $qb = $this->createQueryBuilder('filter');

        foreach ($query->filters as $filter) {
            $qb
                ->andWhere("filter.{$filter->field} {$filter->operator} :{$filter->field}")
                ->setParameter($filter->field, $filter->value);
        }

        foreach ($query->sort as $sort) {
            $qb
                ->addOrderBy("filter.{$sort->field}", $sort->direction);
        }

        return $qb->getQuery()->getResult();
    }
}
