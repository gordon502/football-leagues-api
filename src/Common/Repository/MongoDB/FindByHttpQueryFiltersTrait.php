<?php

namespace App\Common\Repository\MongoDB;

use App\Common\HttpQuery\Filter\HttpQueryFilter;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use MongoDB\BSON\Regex;

/**
 * @method QueryBuilder createQueryBuilder()
 */
trait FindByHttpQueryFiltersTrait
{
    public function findByHttpQueryFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder();

        /** @var HttpQueryFilter $filter */
        foreach ($filters as $filter) {
            if ($filter->operator === '$regex') {
                $qb
                    ->field($filter->field)
                    ->equals($this->sqlLikeToRegex($filter->value));
                continue;
            }

            $qb
                ->field($filter->field)->{$filter->operator}($filter->value);
        }

        return $qb->getQuery()->execute()->toArray();
    }

    private function sqlLikeToRegex(string $sqlLike): Regex
    {
        $regex = str_replace('%', '.*', $sqlLike);
        return new Regex("^$regex$", 'i');
    }
}
