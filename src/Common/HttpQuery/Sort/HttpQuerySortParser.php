<?php

namespace App\Common\HttpQuery\Sort;

use App\Common\HttpQuery\Filter\HttpQueryFilterParserException;
use ReflectionClass;

class HttpQuerySortParser implements HttpQuerySortParserInterface
{
    private const DIRS = ['asc', 'desc'];

    // TODO: better error handling (separate exceptions for each case)
    public function parse(string $sortQuery, string $testedInterface): array
    {
        $reflection = new ReflectionClass($testedInterface);

        // Match patterns like 'field:dir'
        $pattern = '/\b(\w+):(\w+)\b/';

        preg_match_all($pattern, $sortQuery, $matches);
        $pairs = $matches[0];

        if (count($pairs) === 0 && $sortQuery !== '') {
            throw new HttpQuerySortParserException($sortQuery);
        }

        $result = [];
        foreach ($pairs as $pair) {
            $terms = explode(':', $pair);

            if (count($terms) !== 2) {
                throw new HttpQuerySortParserException($sortQuery);
            }

            $objectFieldName = $this->snakeToCamelCase($terms[0]);

            $hasGetter = $reflection->hasMethod('get' . ucfirst($objectFieldName));
            $hasIsser = $reflection->hasMethod('is' . ucfirst($objectFieldName));
            if (!$hasGetter && !$hasIsser) {
                throw new HttpQueryFilterParserException($sortQuery);
            }

            if (!in_array($terms[1], self::DIRS)) {
                throw new HttpQuerySortParserException($sortQuery);
            }

            $result[] = new HttpQuerySort(
                field: $objectFieldName,
                direction: $terms[1],
            );
        }

        return $result;
    }

    private function snakeToCamelCase(string $input): string
    {
        return \lcfirst(\str_replace('_', '', \ucwords($input, '_')));
    }
}
