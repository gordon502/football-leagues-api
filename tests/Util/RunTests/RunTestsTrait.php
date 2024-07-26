<?php

namespace Tests\Util\RunTests;

use GuzzleHttp\Client;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMariaDB;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMongoDB;
use Tests\Util\TestDatabaseTypeEnum;

trait RunTestsTrait
{
    protected readonly TestAvailableResourcesInterface $availableResources;

    public function __construct(
        protected readonly Client $client,
        protected readonly TestDatabaseTypeEnum $databaseType
    ) {
        $this->availableResources = $databaseType->value === TestDatabaseTypeEnum::MariaDB->value
            ? new TestAvailableResourcesMariaDB()
            : new TestAvailableResourcesMongoDB();
    }

    public function runTests(): void
    {
        $reflection = new \ReflectionClass(static::class);

        echo $reflection->getName() . PHP_EOL;

        if (method_exists($this, 'testShouldReturnInitialCollection')) {
            $this->testShouldReturnInitialCollection();
            echo "\t✅  testShouldReturnInitialCollection" . PHP_EOL;
        }

        foreach ($reflection->getMethods() as $method) {
            if ($method->name === 'testShouldReturnInitialCollection') {
                continue;
            }

            if (str_starts_with($method->name, 'testShould')) {
                $method->invoke($this);

                echo "\t✅  {$method->name}" . PHP_EOL;
            }
        }
    }
}
