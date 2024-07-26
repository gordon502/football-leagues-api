<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestDatabaseTypeEnum;

class FilterGreaterThanEqualOperatorTest extends AbstractFilterOperatorTest
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

        $greaterEqualIdCount = count(array_filter(
            $availableUsers,
            fn($user) => $user['id'] >= $sampleId
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:ge:'$sampleId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertCount($greaterEqualIdCount, $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $greaterEqualTrueUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] >= true));
        $greaterEqualFalseUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] >= false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:ge:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($greaterEqualTrueUserCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:ge:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($greaterEqualFalseUserCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithAvatarGreaterEqualNullCount = count(array_filter(
            $availableResources,
            fn($user) => $user['avatar'] >= null
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:ge:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithAvatarGreaterEqualNullCount, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $resourcesWithYearEstablished = array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== null
        );
        $sampleYearEstablished = $resourcesWithYearEstablished[0]['yearEstablished'];
        $resourcesWithGreaterEqualYearEstablishedCount = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] >= $sampleYearEstablished
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:ge:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithGreaterEqualYearEstablishedCount, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $roundsWithGreaterEqualStartDateCount = count(array_filter(
            $rounds,
            fn($article) => $article['standardStartDate'] >= $sampleDate
        ));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:ge:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($roundsWithGreaterEqualStartDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();
        $sampleDate = $articles[0]['postAt'];
        $articlesWithGreaterDateCount = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] >= $sampleDate
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:ge:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($articlesWithGreaterDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $resourcesWithGreaterEqualOrganizationalUnitIdCount = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] >= $sampleOrganizationalUnitId
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:ge:'$sampleOrganizationalUnitId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithGreaterEqualOrganizationalUnitIdCount, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );
        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $articlesWithGreaterEqualSeasonTeamIdCount = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam >= $sampleSeasonTeamId
            )) > 0
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:ge:'$sampleSeasonTeamId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithGreaterEqualSeasonTeamIdCount, $items);
    }
}
