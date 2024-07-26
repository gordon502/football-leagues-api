<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestDatabaseTypeEnum;

class FilterGreaterThanOperatorTest extends AbstractFilterOperatorTest
{
    public function __construct(
        Client $client,
        TestDatabaseTypeEnum $databaseTypeEnum
    ) {
        parent::__construct($client, $databaseTypeEnum);
    }

    protected function testShouldAllowFilterForOwnStringField(): void
    {
        $availableUsers = $this->availableResources->getUsers();
        $sampleId = $availableUsers[0]['id'];

        $greaterThanIdCount = count(array_filter(
            $availableUsers,
            fn($user) => $user['id'] > $sampleId
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:gt:'$sampleId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertCount($greaterThanIdCount, $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $greaterThanTrueUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] > true));
        $greaterThanFalseUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] > false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:gt:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($greaterThanFalseUserCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:gt:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($greaterThanTrueUserCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithAvatarGreaterThanNullCount = count(array_filter(
            $availableResources,
            fn($user) => $user['avatar'] > null
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:gt:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithAvatarGreaterThanNullCount, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $resourcesWithYearEstablished = array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== null
        );
        $sampleYearEstablished = $resourcesWithYearEstablished[0]['yearEstablished'];
        $resourcesWithGreaterYearEstablishedCount = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] > $sampleYearEstablished
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:gt:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithGreaterYearEstablishedCount, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $roundsWithGreaterStartDateCount = count(array_filter(
            $rounds,
            fn($article) => $article['standardStartDate'] > $sampleDate
        ));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:gt:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($roundsWithGreaterStartDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();
        $sampleDate = $articles[0]['postAt'];
        $articlesWithGreaterDateCount = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] > $sampleDate
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:gt:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($articlesWithGreaterDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $resourcesWithGreaterOrganizationalUnitIdCount = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] > $sampleOrganizationalUnitId
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:gt:'$sampleOrganizationalUnitId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithGreaterOrganizationalUnitIdCount, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );
        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $articlesWithGreaterSeasonTeamIdCount = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam > $sampleSeasonTeamId
            )) > 0
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:gt:'$sampleSeasonTeamId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithGreaterSeasonTeamIdCount, $items);
    }
}
