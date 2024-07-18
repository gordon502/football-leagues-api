<?php

namespace Tests\Pagination\Filter;

use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

abstract class AbstractFilterOperatorTest extends Assert implements RunTestsInterface
{
    use RunTestsTrait;

    public function __construct(
        protected readonly Client $client,
        protected readonly TestAvailableResourcesInterface $availableResources
    ) {
    }

    abstract protected function testShouldAllowFilterForOwnStringField(): void;

    abstract protected function testShouldAllowFilterForOwnBoolField(): void;

    abstract protected function testShouldAllowFilterForOwnNullableField(): void;

    abstract protected function testShouldAllowFilterForOwnIntegerField(): void;

    abstract protected function testShouldAllowFilterForDateField(): void;

    abstract protected function testShouldAllowFilterForDateTimeField(): void;

    abstract protected function testShouldAllowFilterForReferenceStringField(): void;

    abstract protected function testShouldAllowFilterForReferenceArrayField(): void;
}
