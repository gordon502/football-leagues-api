<?php

namespace App\Common\HttpQuery\Filter;

use ReflectionClass;

use function trim;

class HttpQueryFilterParser implements HttpQueryFilterParserInterface
{
    private const OPERATORS = [
        'eq', 'ne', 'gt', 'lt', 'gt', 'ge', 'le', 'like'
    ];

    // TODO: better error handling (separate exceptions for each case)
    public function parse(string $filterQuery, string $testedInterface): array
    {
        $reflection = new ReflectionClass($testedInterface);

        // Match patterns like 'term1:term2:'term3''
        $pattern = '/(\w+:\w+:\'[^\']+\'(?:\s*,\s*|$))/';

        preg_match_all($pattern, $filterQuery, $matches);
        $pairs = $matches[0];

        if (count($pairs) === 0 && $filterQuery !== '') {
            throw new HttpQueryFilterParserException($filterQuery);
        }

        $result = [];
        foreach ($pairs as $pair) {
            $terms = explode(':', $pair);

            if (count($terms) !== 3) {
                throw new HttpQueryFilterParserException($filterQuery);
            }

            $objectFieldName = $terms[0];
            $ucObjectFieldName = ucfirst($objectFieldName);

            $hasGetter = $reflection->hasMethod('get' . $ucObjectFieldName);
            $hasIsser = $reflection->hasMethod('is' . $ucObjectFieldName);

            if (!$hasGetter && !$hasIsser) {
                throw new HttpQueryFilterParserException($filterQuery);
            }

            if (!in_array($terms[1], self::OPERATORS)) {
                throw new HttpQueryFilterParserException($filterQuery);
            }

            $result[] = new HttpQueryFilter(
                field: $objectFieldName,
                operator: $this->convertOperator($terms[1]),
                value: trim($terms[2], "'")
            );
        }

        return $result;
    }

    private function convertOperator(string $operator): string
    {
        if ($_ENV['DATABASE_IMPLEMENTATION'] === 'MongoDB') {
            return match ($operator) {
                'eq' => 'equals',
                'ne' => 'notEqual',
                'gt' => 'gt',
                'lt' => 'lt',
                'ge' => 'gte',
                'le' => 'lte',
                'like' => '$regex',
                default => throw new HttpQueryFilterParserException('Invalid operator'),
            };
        }

        return match ($operator) {
            'eq' => '=',
            'ne' => '!=',
            'gt' => '>',
            'lt' => '<',
            'ge' => '>=',
            'le' => '<=',
            'like' => 'LIKE',
            default => throw new HttpQueryFilterParserException('Invalid operator'),
        };
    }
}
