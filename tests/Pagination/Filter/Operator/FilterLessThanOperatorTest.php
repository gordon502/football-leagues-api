<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

class FilterLessThanOperatorTest extends AbstractFilterOperatorTest
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
        $sampleId = $availableUsers[0]['id'];

        $lessThanIdCount = count(array_filter(
            $availableUsers,
            fn($user) => $user['id'] < $sampleId
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:lt:'$sampleId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertCount($lessThanIdCount, $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $lessThanTrueUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] < true));
        $lessThanFalseUserCount = count(array_filter($availableResources, fn($user) => $user['blocked'] < false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:lt:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($lessThanTrueUserCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:lt:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($lessThanFalseUserCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithAvatarLessThanNullCount = count(array_filter(
            $availableResources,
            fn($user) => $user['avatar'] < null
        ));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:lt:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithAvatarLessThanNullCount, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $resourcesWithYearEstablished = array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== null
        );
        $sampleYearEstablished = $resourcesWithYearEstablished[0]['yearEstablished'];
        $resourcesWithLessYearEstablishedCount = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] < $sampleYearEstablished
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:lt:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithLessYearEstablishedCount, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $roundsWithLessStartDateCount = count(array_filter(
            $rounds,
            fn($article) => $article['standardStartDate'] < $sampleDate
        ));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:lt:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($roundsWithLessStartDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();
        $sampleDate = $articles[0]['postAt'];
        $articlesWithLessDateCount = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] < $sampleDate
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:lt:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($articlesWithLessDateCount, $items['data']);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $resourcesWithLessOrganizationalUnitIdCount = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] < $sampleOrganizationalUnitId
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:lt:'$sampleOrganizationalUnitId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithLessOrganizationalUnitIdCount, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );
        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $articlesWithLessSeasonTeamIdCount = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam < $sampleSeasonTeamId
            )) > 0
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:lt:'$sampleSeasonTeamId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithLessSeasonTeamIdCount, $items);
    }
}
