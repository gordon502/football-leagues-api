<?php declare(strict_types=1);

namespace App\Common\OARoleBasedProperty;

use Attribute;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Discriminator;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Xml;
use OpenApi\Generator;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class OARoleBasedProperty extends Property
{
    public function __construct(
        string $description,
        array $roles,
        ?string $property = null,
        string|object|null $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?int $maxProperties = null,
        ?int $minProperties = null,
        ?array $required = null,
        ?array $properties = null,
        string|array|null $type = null,
        ?string $format = null,
        ?Items $items = null,
        ?string $collectionFormat = null,
        mixed $default = Generator::UNDEFINED,
        $maximum = null,
        bool|int|float|null $exclusiveMaximum = null,
        $minimum = null,
        bool|int|float|null $exclusiveMinimum = null,
        ?int $maxLength = null,
        ?int $minLength = null,
        ?int $maxItems = null,
        ?int $minItems = null,
        ?bool $uniqueItems = null,
        ?string $pattern = null,
        array|string|null $enum = null,
        ?Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        mixed $example = Generator::UNDEFINED,
        ?bool $nullable = null,
        ?bool $deprecated = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null,
        AdditionalProperties|bool|null $additionalProperties = null,
        ?array $x = null,
        ?array $attachables = null
    )
    {
        $description = sprintf(
            '%s | Field available for [%s] roles.',
            $description,
            implode(', ', $roles)
        );

        parent::__construct(
            property: $property,
            ref: $ref,
            schema: $schema,
            title: $title,
            description: $description,
            maxProperties: $maxProperties,
            minProperties: $minProperties,
            required: $required,
            properties: $properties,
            type: $type,
            format: $format,
            items: $items,
            collectionFormat: $collectionFormat,
            default: $default,
            maximum: $maximum,
            exclusiveMaximum: $exclusiveMaximum,
            minimum: $minimum,
            exclusiveMinimum: $exclusiveMinimum,
            maxLength: $maxLength,
            minLength: $minLength,
            maxItems: $maxItems,
            minItems: $minItems,
            uniqueItems: $uniqueItems,
            pattern: $pattern,
            enum: $enum,
            discriminator: $discriminator,
            readOnly: $readOnly,
            writeOnly: $writeOnly,
            xml: $xml,
            externalDocs: $externalDocs,
            example: $example,
            nullable: $nullable,
            deprecated: $deprecated,
            allOf: $allOf,
            anyOf: $anyOf,
            oneOf: $oneOf,
            additionalProperties: $additionalProperties,
            x: $x,
            attachables: $attachables
        );
    }
}
