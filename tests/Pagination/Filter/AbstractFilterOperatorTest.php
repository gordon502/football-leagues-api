<?php

namespace Tests\Pagination\Filter;

use PHPUnit\Framework\Assert;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;

abstract class AbstractFilterOperatorTest extends Assert implements RunTestsInterface
{
    use RunTestsTrait;

    abstract protected function testShouldAllowFilterForOwnStringField(): void;

    abstract protected function testShouldAllowFilterForOwnBoolField(): void;

    abstract protected function testShouldAllowFilterForOwnNullableField(): void;

    abstract protected function testShouldAllowFilterForOwnIntegerField(): void;

    abstract protected function testShouldAllowFilterForDateField(): void;

    abstract protected function testShouldAllowFilterForDateTimeField(): void;

    abstract protected function testShouldAllowFilterForReferenceStringField(): void;

    abstract protected function testShouldAllowFilterForReferenceArrayField(): void;
}
