<?php

namespace App\Common\Repository\MongoDB;

use App\Common\HttpQuery\Filter\HttpQueryFilter;
use App\Common\HttpQuery\HttpQuery;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use MongoDB\BSON\Regex;

/**
 * @method QueryBuilder createQueryBuilder()
 */
trait FindByHttpQueryTrait
{
    public function findByHttpQuery(HttpQuery $query): array
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

        return $qb->getQuery()->execute()->toArray();
    }

    private function sqlLikeToRegex(string $sqlLike): Regex
    {
        $regex = str_replace('%', '.*', $sqlLike);
        return new Regex("^$regex$", 'i');
    }
}
