<?php

namespace App\Common\HttpQuery\Filter;

use ReflectionClass;
use ReflectionNamedType;

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

            if (count($terms) < 3) {
                throw new HttpQueryFilterParserException($filterQuery);
            }

            $terms[2] = implode(':', array_slice($terms, 2));
            $terms = array_slice($terms, 0, 3);

            $objectFieldName = $this->extractInternalFieldName($terms[0]);
            $ucObjectFieldName = ucfirst($objectFieldName);
            $getterMethod = $this->extractGetterMethod($reflection, $ucObjectFieldName);

            if (!$getterMethod) {
                throw new HttpQueryFilterParserException($filterQuery);
            }

            if (!in_array($terms[1], self::OPERATORS)) {
                throw new HttpQueryFilterParserException($filterQuery);
            }


            $trimmedValue = trim($terms[2], "'");

            $isValueAllowableNull = $this->isValueAllowableNull($trimmedValue, $reflection, $getterMethod);
            $operator = $this->convertOperator($terms[1], $isValueAllowableNull);
            $isOperatorLike =
                $operator === HttpQueryFilterOperatorEnum::MARIA_DB_LIKE
                || $operator === HttpQueryFilterOperatorEnum::MONGO_DB_LIKE;

            $filterValue = $this->convertValueBasedOnFieldType(
                $trimmedValue,
                $reflection,
                $getterMethod,
                $isOperatorLike
            );

            $result[] = new HttpQueryFilter(
                field: $objectFieldName,
                operator: $operator,
                value: $filterValue,
                isFieldReference: $objectFieldName !== $terms[0],
                isFieldNotStringRegexable: $this->isFieldNotStringRegexable($reflection, $getterMethod),
                isValueDateTimeString: is_string($filterValue) && $this->isValueDateTimeType($filterValue),
            );
        }

        return $result;
    }

    private function convertOperator(string $operator, bool $isValueNull): HttpQueryFilterOperatorEnum
    {
        if ($_ENV['DATABASE_IMPLEMENTATION'] === 'MongoDB') {
            return match ($operator) {
                'eq' => HttpQueryFilterOperatorEnum::MONGO_DB_EQ,
                'ne' => HttpQueryFilterOperatorEnum::MONGO_DB_NE,
                'gt' => HttpQueryFilterOperatorEnum::MONGO_DB_GT,
                'lt' => HttpQueryFilterOperatorEnum::MONGO_DB_LT,
                'ge' => HttpQueryFilterOperatorEnum::MONGO_DB_GE,
                'le' => HttpQueryFilterOperatorEnum::MONGO_DB_LE,
                'like' => HttpQueryFilterOperatorEnum::MONGO_DB_LIKE,
                default => throw new HttpQueryFilterParserException('Invalid operator'),
            };
        }

        return match ($operator) {
            'eq' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_IS_NULL
                : HttpQueryFilterOperatorEnum::MARIA_DB_EQ,
            'ne' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_IS_NOT_NULL
                : HttpQueryFilterOperatorEnum::MARIA_DB_NE,
            'gt' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_IS_NOT_NULL
                : HttpQueryFilterOperatorEnum::MARIA_DB_GT,
            'lt' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_FALSE
                : HttpQueryFilterOperatorEnum::MARIA_DB_LT,
            'ge' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_ALWAYS_TRUE
                : HttpQueryFilterOperatorEnum::MARIA_DB_GE,
            'le' => $isValueNull
                ? HttpQueryFilterOperatorEnum::MARIA_DB_IS_NULL
                : HttpQueryFilterOperatorEnum::MARIA_DB_LE,
            'like' => HttpQueryFilterOperatorEnum::MARIA_DB_LIKE,
            default => throw new HttpQueryFilterParserException('Invalid operator'),
        };
    }

    private function extractInternalFieldName(string $fieldName): string
    {
        if (str_ends_with(lcfirst($fieldName), 'Id')) {
            return substr($fieldName, 0, -2);
        }

        return $fieldName;
    }

    private function extractGetterMethod(ReflectionClass $reflection, string $ucObjectFieldName): ?string
    {
        if ($reflection->hasMethod('get' . $ucObjectFieldName)) {
            return 'get' . $ucObjectFieldName;
        }

        if ($reflection->hasMethod('is' . $ucObjectFieldName)) {
            return 'is' . $ucObjectFieldName;
        }

        return null;
    }

    private function isValueAllowableNull(
        string $value,
        ReflectionClass $modelClass,
        string $getterMethod
    ): bool {
        $returnType = $modelClass->getMethod($getterMethod)->getReturnType();

        if ($value === 'null' && $returnType?->allowsNull()) {
            return true;
        }

        return false;
    }

    private function convertValueBasedOnFieldType(
        string $value,
        ReflectionClass $modelClass,
        string $getterMethod,
        bool $likeOperator = false
    ): string|int|bool|null {
        $returnType = $modelClass->getMethod($getterMethod)->getReturnType();

        if ($this->isValueAllowableNull($value, $modelClass, $getterMethod)) {
            return null;
        }

        if ($returnType === null) {
            return $value;
        }

        if ($returnType instanceof ReflectionNamedType) {
            if ($returnType->getName() === 'string') {
                return $value;
            }

            if ($returnType->getName() === 'bool') {
                return match ($value) {
                    'false' => false,
                    default => true,
                };
            }

            if ($likeOperator) {
                return $value;
            }

            if ($returnType->getName() === 'int') {
                return (int) $value;
            }
        }

        return $value;
    }

    private function isFieldNotStringRegexable(
        ReflectionClass $modelClass,
        string $getterMethod
    ): bool {
        $returnType = $modelClass->getMethod($getterMethod)->getReturnType();

        if ($returnType === null) {
            return false;
        }

        if ($returnType instanceof ReflectionNamedType) {
            return $returnType->getName() !== 'string';
        }

        return false;
    }

    private function isValueDateTimeType(string $value): bool
    {
        return (bool) strtotime($value);
    }
}
