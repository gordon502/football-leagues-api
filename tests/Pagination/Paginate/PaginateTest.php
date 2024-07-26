<?php

namespace Tests\Pagination\Paginate;

use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use Tests\Modules\User\UserControllerTest;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMariaDB;
use Tests\Util\TestAvailableResources\TestAvailableResourcesMongoDB;
use Tests\Util\TestDatabaseTypeEnum;

class PaginateTest extends Assert implements RunTestsInterface
{
    use RunTestsTrait;

    private readonly TestAvailableResourcesInterface $availableResources;

    public function __construct(
        private readonly Client $client,
        TestDatabaseTypeEnum $databaseType
    ) {
        $this->availableResources = $databaseType->value === TestDatabaseTypeEnum::MariaDB->value
            ? new TestAvailableResourcesMariaDB()
            : new TestAvailableResourcesMongoDB();
    }

    public function testShouldCheckIfPaginationWorks(): void
    {
        $allUsersCount = count($this->availableResources->getUsers());

        $responseFirstPage = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'page' => '1',
                'limit' => '2'
            ]
        ]);
        $firstPageContents = json_decode($responseFirstPage->getBody()->getContents(), true);
        $responseSecondPage = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'page' => '2',
                'limit' => '2'
            ]
        ]);
        $secondPageContents = json_decode($responseSecondPage->getBody()->getContents(), true);

        $this->assertCount(2, $secondPageContents['data']);
        $this->assertEquals(2, $secondPageContents['pagination']['limit']);
        $this->assertEquals(2, $secondPageContents['pagination']['currentPage']);
        $this->assertEquals(ceil($allUsersCount / 2), $secondPageContents['pagination']['totalPages']);
        $this->assertEquals($allUsersCount, $secondPageContents['pagination']['total']);
        $this->assertNotEquals($secondPageContents, $firstPageContents);
    }
}
