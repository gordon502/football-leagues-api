<?php

namespace App\Common\Repository\MongoDB;

use App\Common\HttpQuery\Filter\HttpQueryFilterOperatorEnum;
use App\Common\HttpQuery\HttpQuery;
use App\Common\Pagination\PaginatedQueryResultInterface;
use App\Common\Pagination\PaginationOutOfBoundException;
use App\Common\Repository\PaginatedQueryResult;
use DateTime;
use DateTimeZone;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use MongoDB\BSON\Regex;

/**
 * @method QueryBuilder createQueryBuilder()
 */
trait FindByHttpQueryTrait
{
    /**
     * @throws MongoDBException
     */
    public function findByHttpQuery(HttpQuery $query): PaginatedQueryResultInterface
    {
        $qb = $this->createQueryBuilder();

        foreach ($query->filters as $filter) {
            $fieldToFilter = $filter->field;
            if ($filter->isFieldReference) {
                $fieldToFilter = $filter->field . '.$id';
            }

            if ($filter->operator === HttpQueryFilterOperatorEnum::MONGO_DB_LIKE) {
                if (is_bool($filter->value)) {
                    $qb
                        ->field($fieldToFilter)
                        ->equals($filter->value);
                    continue;
                }

                if (is_null($filter->value)) {
                    $qb
                        ->field($fieldToFilter)
                        ->equals(null);
                    continue;
                }

                if ($filter->isFieldNotStringRegexable && !$filter->isFieldReference) {
                    $timezoneOffsetSeconds =
                        (new DateTime('now', new DateTimeZone(date_default_timezone_get())))->getOffset();

                    $slashedPattern = addslashes($this->sqlLikeToRegex($filter->value)->getPattern());

                    $isoStringInField =
                        "new Date(this.{$fieldToFilter}.getTime() + {$timezoneOffsetSeconds} * 1000)"
                        . ".toISOString()"
                        . "?.slice(0, -5)"
                        . ".replace('T', ' ')"
                        // phpcs:ignore
                        . ".replace(' ' + new Date(new Date(this.{$fieldToFilter}.toISOString().split('T')[0]).getTime() + {$timezoneOffsetSeconds} * 1000).toLocaleTimeString(), '')"
                        . ".match(/$slashedPattern/i)";

                    $qb->where(
                        "typeof this.{$fieldToFilter} === 'object' && 'toISOString' in this.{$fieldToFilter} "
                        . "? $isoStringInField "
                        . ": this.{$fieldToFilter}?.toString().match(/{$slashedPattern}/i)"
                    );

                    continue;
                }

                $qb
                    ->field($fieldToFilter)
                    ->equals($this->sqlLikeToRegex($filter->value));
                continue;
            }

            $value = $filter->value;
            if ($filter->isValueDateTimeString && !str_contains($value, ':')) {
                $value = $filter->value . 'T00:00:00Z';
            }

            $qb
                ->field($fieldToFilter)->{$filter->operator->value}($value);
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
        $regex = str_replace('_', '.', $regex);
        return new Regex("^$regex$", 'i');
    }
}
