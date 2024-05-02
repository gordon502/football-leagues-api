<?php

namespace App\Common\Repository\MariaDB;

use App\Common\HttpQuery\Filter\HttpQueryFilter;
use Doctrine\ORM\QueryBuilder;

/**
 * @method QueryBuilder createQueryBuilder(string $alias, ?string $indexBy = null)
 */
trait FindByHttpQueryFiltersTrait
{
    public function findByHttpQueryFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('filter');

        /** @var HttpQueryFilter $filter */
        foreach ($filters as $filter) {
            $qb
                ->andWhere("filter.{$filter->field} {$filter->operator} :{$filter->field}")
                ->setParameter($filter->field, $filter->value);
        }

        return $qb->getQuery()->getResult();
    }
}
