<?php

namespace Tests\Pagination\Sort;

use PHPUnit\Framework\Assert;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Util\RunTests\RunTestsInterface;
use Tests\Util\RunTests\RunTestsTrait;

class SortTest extends Assert implements RunTestsInterface
{
    use RunTestsTrait;

    public function testShouldAllowSortByOwnStringField(): void
    {
        $sortedUsersAsc = $this->availableResources->getUsers();
        $sortedUsersDesc = $this->availableResources->getUsers();

        usort($sortedUsersAsc, fn ($a, $b) => $a['id'] <=> $b['id']);
        usort($sortedUsersDesc, fn ($a, $b) => $b['id'] <=> $a['id']);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'id:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedUsersAsc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnBoolField(): void
    {
        $sortedUsersAsc = $this->availableResources->getUsers();
        $sortedUsersDesc = $this->availableResources->getUsers();

        usort($sortedUsersAsc, fn ($a, $b) => $a['blocked'] <=> $b['blocked']);
        usort($sortedUsersDesc, fn ($a, $b) => $b['blocked'] <=> $a['blocked']);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'blocked:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedUsersAsc), $this->mapByIdField($items));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'blocked:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedUsersDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnNullableField(): void
    {
        $sortedUsersAsc = $this->availableResources->getUsers();
        $sortedUsersDesc = $this->availableResources->getUsers();

        usort($sortedUsersAsc, fn ($a, $b) => $a['avatar'] <=> $b['avatar']);
        usort($sortedUsersDesc, fn ($a, $b) => $b['avatar'] <=> $a['avatar']);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'avatar:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedUsersAsc), $this->mapByIdField($items));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'avatar:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedUsersDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnIntegerField(): void
    {
        $sortedTeamsAsc = $this->availableResources->getTeams();
        $sortedTeamsDesc = $this->availableResources->getTeams();

        usort($sortedTeamsAsc, fn ($a, $b) => $a['yearEstablished'] <=> $b['yearEstablished']);
        usort($sortedTeamsDesc, fn ($a, $b) => $b['yearEstablished'] <=> $a['yearEstablished']);

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'yearEstablished:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedTeamsAsc), $this->mapByIdField($items));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'yearEstablished:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedTeamsDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnDateField(): void
    {
        $sortedRoundsAsc = $this->availableResources->getRounds();
        $sortedRoundsDesc = $this->availableResources->getRounds();

        usort($sortedRoundsAsc, fn ($a, $b) => $a['standardStartDate'] <=> $b['standardStartDate']);
        usort($sortedRoundsDesc, fn ($a, $b) => $b['standardStartDate'] <=> $a['standardStartDate']);

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'standardStartDate:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedRoundsAsc), $this->mapByIdField($items));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'standardStartDate:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedRoundsDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnDateTimeField(): void
    {
        $sortedArticlesAsc = $this->availableResources->getArticles();
        $sortedArticlesDesc = $this->availableResources->getArticles();

        usort($sortedArticlesAsc, fn ($a, $b) => $a['postAt'] <=> $b['postAt']);
        usort($sortedArticlesDesc, fn ($a, $b) => $b['postAt'] <=> $a['postAt']);

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'postAt:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedArticlesAsc), $this->mapByIdField($items));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'postAt:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedArticlesDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnReferenceStringField(): void
    {
        $sortedTeamsAsc = $this->availableResources->getTeams();
        $sortedTeamsDesc = $this->availableResources->getTeams();

        usort($sortedTeamsAsc, function (array $a, array $b) {
            if ($a['organizationalUnitId'] === $b['organizationalUnitId']) {
                return $a['id'] <=> $b['id'];
            }

            return $a['organizationalUnitId'] <=> $b['organizationalUnitId'];
        });
        usort($sortedTeamsDesc, function (array $a, array $b) {
            if ($a['organizationalUnitId'] === $b['organizationalUnitId']) {
                return $b['id'] <=> $a['id'];
            }

            return $b['organizationalUnitId'] <=> $a['organizationalUnitId'];
        });

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'organizationalUnitId:asc,id:asc'
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedTeamsAsc), $this->mapByIdField($items));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'organizationalUnitId:desc,id:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedTeamsDesc), $this->mapByIdField($items));
    }

    public function testShouldAllowSortByOwnReferenceArrayField(): void
    {
        $sortedArticlesAsc = $this->availableResources->getArticles();
        $sortedArticlesDesc = $this->availableResources->getArticles();

        usort($sortedArticlesAsc, fn ($a, $b) => $a['seasonTeamsId'] <=> $b['seasonTeamsId']);
        usort($sortedArticlesDesc, fn ($a, $b) => $b['seasonTeamsId'] <=> $a['seasonTeamsId']);

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'seasonTeamsId:asc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedArticlesAsc), $this->mapByIdField($items));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'sort' => 'seasonTeamsId:desc'
            ]
        ]);
        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertEquals($this->mapByIdField($sortedArticlesDesc), $this->mapByIdField($items));
    }

    private function mapByIdField(array $entries): array
    {
        return array_map(fn($entry) => $entry['id'], $entries);
    }
}
