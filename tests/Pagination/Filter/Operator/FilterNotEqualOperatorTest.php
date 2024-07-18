<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

class FilterNotEqualOperatorTest extends AbstractFilterOperatorTest
{
    public function __construct(
        Client $client,
        TestAvailableResourcesInterface $availableResources
    ) {
        parent::__construct($client, $availableResources);
    }

    protected function testShouldAllowFilterForOwnStringField(): void
    {
        $availableUsers = $this->availableResources->getUsers();

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:ne:'" . $availableUsers[0]['id'] . "'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertCount(count($availableUsers) - 1, $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $blockedUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === true));
        $freeUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:ne:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($freeUsersCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:ne:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($blockedUsersCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithAvatar = count(array_filter($availableResources, fn($user) => $user['avatar'] !== null));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:ne:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithAvatar, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $resourcesWithYearEstablished = array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== null
        );
        $sampleYearEstablished = $resourcesWithYearEstablished[0]['yearEstablished'];
        $resourcesWithNotSameYearEstablishedCount = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== $sampleYearEstablished
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:ne:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithNotSameYearEstablishedCount, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $roundsWithNotSameStartDate = count(array_filter(
            $rounds,
            fn($article) => $article['standardStartDate'] !== $sampleDate
        ));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:ne:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($roundsWithNotSameStartDate, $items['data']);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();
        $sampleDate = $articles[0]['postAt'];
        $articlesWithNotSameDate = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] !== $sampleDate
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:ne:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($articlesWithNotSameDate, $items['data']);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $resourcesWithNotSameOrganizationalUnitIdCount = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] !== $sampleOrganizationalUnitId
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:ne:'$sampleOrganizationalUnitId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithNotSameOrganizationalUnitIdCount, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );
        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $articlesWithNotSameSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam !== $sampleSeasonTeamId
            )) > 0
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:ne:'$sampleSeasonTeamId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithNotSameSeasonTeamId, $items);
    }
}
