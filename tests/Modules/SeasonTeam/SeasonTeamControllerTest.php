<?php

namespace Tests\Modules\SeasonTeam;

use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class SeasonTeamControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'season-teams';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$seasonTeams = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season Team $i",
                    'teamId' => $this->randomTeamId(),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasonTeams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season Team $i",
                    'teamId' => $this->randomTeamId(),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasonTeams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season Team $i",
                    'teamId' => $this->randomTeamId(),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasonTeams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'name' => "Season Team",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$seasonTeams as $resource) {
            $response = $this->client->get("{$this->endpoint}/{$resource['id']}", [
                'headers' => ['Authorization' => "Bearer $token"],
            ]);

            $this->assertEquals(200, $response->getStatusCode());

            $json = json_decode($response->getBody()->getContents(), true);

            $this->assertEquals($resource, $json);
        }
    }

    protected function testShouldReturnCollectionOfAllCreatedResources(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(count(TestAvailableResources::$seasonTeams), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'teamId',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'teamId',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'teamId',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'teamId',
            'seasonId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'teamId',
            'seasonId'
        ];

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasonTeams[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasonTeams[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasonTeams[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByEditor(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByUser(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team Updated",
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByGuest(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByGuest(): void
    {
        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'name' => "Season Team Updated",
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeam = array_pop(TestAvailableResources::$seasonTeams);
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $seasonTeam = array_pop(TestAvailableResources::$seasonTeams);
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $seasonTeam = array_pop(TestAvailableResources::$seasonTeams);
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$seasonTeams[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonDeletesSeasonTeamsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeam = array_pop(TestAvailableResources::$seasonTeams);
        $relatedSeasonsTeams = [
            $seasonTeam,
            ...array_filter(
                TestAvailableResources::$seasonTeams,
                fn($st) => $st['seasonId'] === $seasonTeam['seasonId']
            )
        ];

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "seasons/{$seasonTeam['seasonId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$seasons = array_values(array_filter(
            TestAvailableResources::$seasons,
            fn($s) => $s['id'] !== $seasonTeam['seasonId']
        ));

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$seasonTeams = array_values(array_filter(
            TestAvailableResources::$seasonTeams,
            fn($st) => $st['seasonId'] !== $seasonTeam['seasonId']
        ));
    }

    protected function testShouldCheckIfDeletionOfTeamDeletesSeasonTeamsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeam = array_pop(TestAvailableResources::$seasonTeams);
        $relatedSeasonsTeams = [
            $seasonTeam,
            ...array_filter(
                TestAvailableResources::$seasonTeams,
                fn($st) => $st['teamId'] === $seasonTeam['teamId']
            )
        ];

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "teams/{$seasonTeam['teamId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$teams = array_values(array_filter(
            TestAvailableResources::$teams,
            fn($t) => $t['id'] !== $seasonTeam['teamId']
        ));

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$seasonTeams = array_values(array_filter(
            TestAvailableResources::$seasonTeams,
            fn($st) => $st['teamId'] !== $seasonTeam['teamId']
        ));
    }

    private function randomTeamId(): string
    {
        return TestAvailableResources::$teams[
            array_rand(TestAvailableResources::$teams)
        ]['id'];
    }

    private function randomSeasonId(): string
    {
        return TestAvailableResources::$seasons[
            array_rand(TestAvailableResources::$seasons)
        ]['id'];
    }
}
