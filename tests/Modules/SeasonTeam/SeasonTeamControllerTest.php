<?php

namespace Tests\Modules\SeasonTeam;

use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class SeasonTeamControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'season-teams';

    public function __construct(Client $client, TestDatabaseTypeEnum $databaseType)
    {
        parent::__construct($client, $databaseType);

        $this->endpoint = self::DEFAULT_ENDPOINT;
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
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

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

        foreach ($this->availableResources->getSeasonTeams() as $resource) {
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

        $this->assertCount(count($this->availableResources->getSeasonTeams()), $json['data']);
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

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Team 1 Updated",
                'teamId' => $this->randomTeamId(),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());
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
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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
        $id = $this->availableResources->getSeasonTeams()[0]['id'];

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

        $seasonTeam = $this->availableResources->getSeasonTeams()[0];
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $seasonTeam = $this->availableResources->getSeasonTeams()[0];
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $seasonTeam = $this->availableResources->getSeasonTeams()[0];
        $id = $seasonTeam['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getSeasonTeams()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonDeletesSeasonTeamsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeam = $this->availableResources->getSeasonTeams()[0];
        $relatedSeasonsTeams = [
            $seasonTeam,
            ...array_filter(
                array_slice($this->availableResources->getSeasonTeams(), 1),
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

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfDeletionOfTeamDeletesSeasonTeamsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeam = $this->availableResources->getSeasonTeams()[0];
        $relatedSeasonsTeams = [
            $seasonTeam,
            ...array_filter(
                array_slice($this->availableResources->getSeasonTeams(), 1),
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

        foreach ($relatedSeasonsTeams as $relatedSeasonTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeasonTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    private function randomTeamId(): string
    {
        $teams = $this->availableResources->getTeams();

        return $teams[array_rand($teams)]['id'];
    }

    private function randomSeasonId(): string
    {
        $seasons = $this->availableResources->getSeasons();

        return $seasons[array_rand($seasons)]['id'];
    }
}
