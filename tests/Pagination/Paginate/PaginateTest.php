<?php

namespace Tests\Pagination\Paginate;

use PHPUnit\Framework\Assert;
use Tests\Modules\User\UserControllerTest;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;

class PaginateTest extends Assert implements RunTestsInterface
{
    use RunTestsTrait;

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
