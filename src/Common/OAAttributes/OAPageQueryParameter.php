<?php

namespace App\Common\OAAttributes;

use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\XmlContent;
use OpenApi\Generator;

#[\Attribute(
    \Attribute::TARGET_CLASS |
    \Attribute::TARGET_METHOD |
    \Attribute::TARGET_PROPERTY |
    \Attribute::TARGET_PARAMETER |
    \Attribute::IS_REPEATABLE
)]
class OAPageQueryParameter extends Parameter
{
    public function __construct(
        ?string $parameter = null,
        ?bool $deprecated = null,
        ?bool $allowEmptyValue = null,
        string|object|null $ref = null,
        mixed $example = Generator::UNDEFINED,
        ?array $examples = null,
        array|JsonContent|XmlContent|Attachable|null $content = null,
        ?string $style = null,
        ?bool $explode = null,
        ?bool $allowReserved = null,
        ?array $spaceDelimited = null,
        ?array $pipeDelimited = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct(
            $parameter,
            'page',
            'Paginate the result - page number. Default is 1.',
            'query',
            false,
            $deprecated,
            $allowEmptyValue,
            $ref,
            new Schema(type: 'string'),
            $example,
            $examples,
            $content,
            $style,
            $explode,
            $allowReserved,
            $spaceDelimited,
            $pipeDelimited,
            $x,
            $attachables
        );
    }
}
