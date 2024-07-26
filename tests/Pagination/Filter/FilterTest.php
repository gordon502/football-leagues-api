<?php

namespace Tests\Pagination\Filter;

use Tests\Pagination\Filter\Operator\FilterEqualOperatorTest;
use Tests\Pagination\Filter\Operator\FilterGreaterThanEqualOperatorTest;
use Tests\Pagination\Filter\Operator\FilterGreaterThanOperatorTest;
use Tests\Pagination\Filter\Operator\FilterLessThanEqualOperatorTest;
use Tests\Pagination\Filter\Operator\FilterLessThanOperatorTest;
use Tests\Pagination\Filter\Operator\FilterLikeOperatorTest;
use Tests\Pagination\Filter\Operator\FilterNotEqualOperatorTest;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;

readonly class FilterTest implements RunTestsInterface
{
    use RunTestsTrait;

    public function runTests(): void
    {
        $filterEqualOperatorTest = new FilterEqualOperatorTest($this->client, $this->databaseType);
        $filterNotEqualOperatorTest = new FilterNotEqualOperatorTest($this->client, $this->databaseType);
        $filterGreaterThanOperatorTest = new FilterGreaterThanOperatorTest($this->client, $this->databaseType);
        $filterGreaterThanEqualOperatorTest = new FilterGreaterThanEqualOperatorTest(
            $this->client,
            $this->databaseType
        );
        $filterLessThanOperatorTest = new FilterLessThanOperatorTest($this->client, $this->databaseType);
        $filterLessThanEqualOperatorTest = new FilterLessThanEqualOperatorTest(
            $this->client,
            $this->databaseType
        );
        $filterLikeOperatorTest = new FilterLikeOperatorTest($this->client, $this->databaseType);

        $filterEqualOperatorTest->runTests();
        $filterNotEqualOperatorTest->runTests();
        $filterGreaterThanOperatorTest->runTests();
        $filterGreaterThanEqualOperatorTest->runTests();
        $filterLessThanOperatorTest->runTests();
        $filterLessThanEqualOperatorTest->runTests();
        $filterLikeOperatorTest->runTests();
    }
}
