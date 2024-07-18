<?php

namespace Tests\Pagination\Filter;

use GuzzleHttp\Client;
use Tests\Pagination\Filter\Operator\FilterEqualOperatorTest;
use Tests\Pagination\Filter\Operator\FilterNotEqualOperatorTest;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMariaDB;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMongoDB;
use Tests\Util\TestDatabaseTypeEnum;

readonly class FilterTest implements RunTestsInterface
{
    private TestAvailableResourcesInterface $availableResources;

    public function __construct(
        private Client $client,
        TestDatabaseTypeEnum $databaseType
    ) {
        $this->availableResources = $databaseType->value === TestDatabaseTypeEnum::MariaDB->value
            ? new TestAvailableResourcesMariaDB()
            : new TestAvailableResourcesMongoDB();
    }

    public function runTests(): void
    {
        $filterEqualOperatorTest = new FilterEqualOperatorTest($this->client, $this->availableResources);
        $filterNotEqualOperatorTest = new FilterNotEqualOperatorTest($this->client, $this->availableResources);

        $filterEqualOperatorTest->runTests();
        $filterNotEqualOperatorTest->runTests();
    }
}
