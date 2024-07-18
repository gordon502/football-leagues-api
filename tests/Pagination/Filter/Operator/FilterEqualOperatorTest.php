<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

class FilterEqualOperatorTest extends AbstractFilterOperatorTest
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
                'filter' => "id:eq:'" . $availableUsers[0]['id'] . "'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];

        $this->assertCount(1, $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $blockedUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === true));
        $freeUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:eq:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($blockedUsersCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:eq:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($freeUsersCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithNullAvatar = count(array_filter($availableResources, fn($user) => $user['avatar'] === null));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:eq:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithNullAvatar, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $resourcesWithYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] !== null
        ));
        $sampleYearEstablished = $availableResources[0]['yearEstablished'];
        $resourcesWithSameYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] === $sampleYearEstablished
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:eq:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameYearEstablished, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $roundsWithSameStartDate = count(array_filter(
            $rounds,
            fn($article) => $article['standardStartDate'] === $sampleDate
        ));

        $response = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:eq:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($roundsWithSameStartDate, $items['data']);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();
        $sampleDate = $articles[0]['postAt'];
        $articlesWithSameDate = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] === $sampleDate
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:eq:'$sampleDate'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true);
        $this->assertCount($articlesWithSameDate, $items['data']);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();
        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $resourcesWithSameOrganizationalUnitId = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] === $sampleOrganizationalUnitId
        ));

        $response = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:eq:'$sampleOrganizationalUnitId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameOrganizationalUnitId, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );
        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $articlesWithSameSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam === $sampleSeasonTeamId
            )) > 0
        ));

        $response = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:eq:'$sampleSeasonTeamId'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSameSeasonTeamId, $items);
    }
}
