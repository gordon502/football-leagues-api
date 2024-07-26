<?php

namespace App\Common\HttpQuery\Filter;

enum HttpQueryFilterOperatorEnum: string
{
    case MARIA_DB_EQ = '=';
    case MARIA_DB_NE = '!=';
    case MARIA_DB_GT = '>';
    case MARIA_DB_GE = '>=';
    case MARIA_DB_LT = '<';
    case MARIA_DB_LE = '<=';
    case MARIA_DB_LIKE = 'LIKE';
    case MARIA_DB_IS_NULL = 'IS NULL';
    case MARIA_DB_IS_NOT_NULL = 'IS NOT NULL';
    case MARIA_DB_ALWAYS_TRUE = '1 = 1';
    case MARIA_DB_ALWAYS_FALSE = '1 = 2';

    case MONGO_DB_EQ = 'equals';
    case MONGO_DB_NE = 'notEqual';
    case MONGO_DB_GT = 'gt';
    case MONGO_DB_GE = 'gte';
    case MONGO_DB_LT = 'lt';
    case MONGO_DB_LE = 'lte';
    case MONGO_DB_LIKE = '$regex';
}