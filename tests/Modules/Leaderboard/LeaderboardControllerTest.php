<?php

namespace Tests\Modules\Leaderboard;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class LeaderboardControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'leaderboards';

    private const READABLE_FIELDS = [
        'id',
        'place',
        'matchesPlayed',
        'points',
        'wins',
        'draws',
        'losses',
        'goalsScored',
        'goalsConceded',
        'homeGoalsScored',
        'homeGoalsConceded',
        'awayGoalsScored',
        'awayGoalsConceded',
        'promotedToHigherDivision',
        'eligibleForPromotionBargaining',
        'eligibleForRetentionBargaining',
        'relegatedToLowerDivision',
        'directMatchesPlayed',
        'directMatchesPoints',
        'directMatchesWins',
        'directMatchesDraws',
        'directMatchesLosses',
        'directMatchesGoalsScored',
        'directMatchesGoalsConceded',
        'annotation',
        'seasonTeamId',
        'seasonId',
    ];

    public function __construct(Client $client, TestDatabaseTypeEnum $databaseType)
    {
        parent::__construct($client, $databaseType);

        $this->endpoint = self::DEFAULT_ENDPOINT;
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 0; $i <= 1; $i++) {
            $response = $this->createLeaderboardRequest($this->availableResources->getSeasonTeams()[$i], $token);

            $this->assertEquals(201, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 2; $i <= 3; $i++) {
            $response = $this->createLeaderboardRequest($this->availableResources->getSeasonTeams()[$i], $token);

            $this->assertEquals(201, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 4; $i <= 5; $i++) {
            $response = $this->createLeaderboardRequest($this->availableResources->getSeasonTeams()[$i], $token);

            $this->assertEquals(201, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->createLeaderboardRequest($this->availableResources->getSeasonTeams()[0], $token);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->createLeaderboardRequest($this->availableResources->getSeasonTeams()[0]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach ($this->availableResources->getLeaderboards() as $resource) {
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

        $this->assertCount(count($this->availableResources->getLeaderboards()), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse(self::READABLE_FIELDS, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse(self::READABLE_FIELDS, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse(self::READABLE_FIELDS, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse(self::READABLE_FIELDS, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse(self::READABLE_FIELDS, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->updateLeaderboardRequest($this->availableResources->getLeaderboards()[0], $token);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->updateLeaderboardRequest($this->availableResources->getLeaderboards()[0], $token);

        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->updateLeaderboardRequest($this->availableResources->getLeaderboards()[0], $token);

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

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'place' => 2,
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
        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'place' => 2,
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $leaderboard = $this->availableResources->getLeaderboards()[0];
        $id = $leaderboard['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $leaderboard = $this->availableResources->getLeaderboards()[0];
        $id = $leaderboard['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $leaderboard = $this->availableResources->getLeaderboards()[0];
        $id = $leaderboard['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonDeletesLeaderboardsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $leaderboard = $this->availableResources->getLeaderboards()[0];
        $relatedLeaderboards = [
            $leaderboard,
            ...array_filter(
                array_slice($this->availableResources->getLeaderboards(), 1),
                fn($l) => $l['seasonId'] === $leaderboard['seasonId']
            )
        ];

        foreach ($relatedLeaderboards as $relatedLeaderboard) {
            $response = $this->client->get("{$this->endpoint}/{$relatedLeaderboard['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "seasons/{$leaderboard['seasonId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());

        foreach ($relatedLeaderboards as $relatedLeaderboard) {
            $response = $this->client->get("{$this->endpoint}/{$relatedLeaderboard['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfDeletionOfSeasonTeamDeletesLeaderboardsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $leaderboard = $this->availableResources->getLeaderboards()[0];
        $relatedLeaderboards = [
            $leaderboard,
            ...array_filter(
                array_slice($this->availableResources->getLeaderboards(), 1),
                fn($l) => $l['seasonTeamId'] === $leaderboard['seasonTeamId']
            )
        ];

        foreach ($relatedLeaderboards as $relatedLeaderboard) {
            $response = $this->client->get("{$this->endpoint}/{$relatedLeaderboard['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "season-teams/{$leaderboard['seasonTeamId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());

        foreach ($relatedLeaderboards as $relatedLeaderboard) {
            $response = $this->client->get("{$this->endpoint}/{$relatedLeaderboard['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    private function testShouldAllowBatchCreateLeaderboardsForAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody($this->availableResources->getSeasonTeams()[7]),
                    $this->createBody($this->availableResources->getSeasonTeams()[8]),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertCount(2, $json);
    }

    private function testShouldAllowBatchCreateLeaderboardsForModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody($this->availableResources->getSeasonTeams()[9]),
                    $this->createBody($this->availableResources->getSeasonTeams()[10]),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(2, $json);
    }

    private function testShouldAllowBatchCreateLeaderboardsForEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody($this->availableResources->getSeasonTeams()[11]),
                    $this->createBody($this->availableResources->getSeasonTeams()[12]),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(2, $json);
    }

    private function testShouldNotAllowBatchCreateLeaderboardsForUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody($this->availableResources->getSeasonTeams()[7]),
                    $this->createBody($this->availableResources->getSeasonTeams()[8]),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    private function testShouldNotAllowBatchCreateLeaderboardsForGuest(): void
    {
        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody($this->availableResources->getSeasonTeams()[7]),
                    $this->createBody($this->availableResources->getSeasonTeams()[8]),
                ]
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    private function testShouldAllowBatchUpdateLeaderboardsForAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->batchUpdateSampleRequest($token);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertCount(2, $json);
    }

    private function testShouldAllowBatchUpdateLeaderboardsForModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->batchUpdateSampleRequest($token);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(2, $json);
    }

    private function testShouldAllowBatchUpdateLeaderboardsForEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->batchUpdateSampleRequest($token);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(2, $json);
    }

    private function testShouldNotAllowBatchUpdateLeaderboardsForUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->batchUpdateSampleRequest($token);

        $this->assertEquals(403, $response->getStatusCode());
    }

    private function testShouldNotAllowBatchUpdateLeaderboardsForGuest(): void
    {
        $response = $this->batchUpdateSampleRequest();

        $this->assertEquals(401, $response->getStatusCode());
    }

    private function testShouldNotAllowToAssignSeasonTeamAlreadyAssignedWhileCreate(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->client->post($this->endpoint, [
            'json' => $this->createBody($this->availableResources->getSeasonTeams()[7]),
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function testShouldNotAllowToAssignSeasonTeamAlreadyAssignedWhileUpdate(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getLeaderboards()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/$id", [
            'json' => $this->createBody($this->availableResources->getSeasonTeams()[7]),
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function testShouldNotAllowToAssignSeasonTeamAlreadyAssignedWhileBatchCreate(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->client->post("{$this->endpoint}/batch-create", [
            'json' => [
                'leaderboards' => [
                    $this->createBody(
                        $this->availableResources->getSeasonTeams()[7],
                    ),
                    $this->createBody(
                        $this->availableResources->getSeasonTeams()[8],
                    ),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function testShouldNotAllowToAssignSeasonTeamAlreadyAssignedWhileBatchUpdate(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->client->post("{$this->endpoint}/batch-update", [
            'json' => [
                'leaderboards' => [
                    $this->createBody(
                        $this->availableResources->getSeasonTeams()[7],
                        $this->availableResources->getLeaderboards()[0]['id']
                    ),
                    $this->createBody(
                        $this->availableResources->getSeasonTeams()[8],
                        $this->availableResources->getLeaderboards()[1]['id']
                    ),
                ]
            ],
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }

    private function createLeaderboardRequest(array $seasonTeam, ?string $token = null): ResponseInterface
    {
        $config = [
            'json' => $this->createBody($seasonTeam)
        ];

        if ($token) {
            $config['headers'] = ['Authorization' => "Bearer $token"];
        }

        return $this->client->post($this->endpoint, $config);
    }

    private function updateLeaderboardRequest(array $leaderboard, ?string $token = null): ResponseInterface
    {
        $config = [
            'json' => $this->createBody([
                'id' => $leaderboard['seasonTeamId'],
                'seasonId' => $leaderboard['seasonId'],
            ])
        ];

        if ($token) {
            $config['headers'] = ['Authorization' => "Bearer $token"];
        }

        return $this->client->put("{$this->endpoint}/{$leaderboard['id']}", $config);
    }

    private function createBody(array $seasonTeam, ?string $id = null): array
    {
        $body = [
            'place' => 69,
            'matchesPlayed' => 0,
            'points' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goalsScored' => 0,
            'goalsConceded' => 0,
            'homeGoalsScored' => 0,
            'homeGoalsConceded' => 0,
            'awayGoalsScored' => 0,
            'awayGoalsConceded' => 0,
            'promotedToHigherDivision' => true,
            'eligibleForPromotionBargaining' => true,
            'eligibleForRetentionBargaining' => true,
            'relegatedToLowerDivision' => true,
            'directMatchesPlayed' => 0,
            'directMatchesPoints' => 0,
            'directMatchesWins' => 0,
            'directMatchesDraws' => 0,
            'directMatchesLosses' => 0,
            'directMatchesGoalsScored' => 69,
            'directMatchesGoalsConceded' => 69,
            'annotation' => "string",
            'seasonTeamId' => $seasonTeam['id'],
            'seasonId' => $seasonTeam['seasonId']
        ];

        if ($id) {
            $body['id'] = $id;
        }

        return $body;
    }

    private function batchUpdateSampleRequest(?string $token = null): ResponseInterface
    {
        $headers = [];
        if ($token) {
            $headers = ['Authorization' => "Bearer $token"];
        }

        $leaderboard0 = $this->availableResources->getLeaderboards()[0];
        $leaderboard1 = $this->availableResources->getLeaderboards()[1];
        $response = $this->client->post("{$this->endpoint}/batch-update", [
            'json' => [
                'leaderboards' => [
                    $this->createBody(
                        $this->getSeasonTeamFromLeaderboard($leaderboard0),
                        $leaderboard0['id'],
                    ),
                    $this->createBody(
                        $this->getSeasonTeamFromLeaderboard($leaderboard1),
                        $leaderboard1['id']
                    ),
                ]
            ],
            'headers' => $headers
        ]);

        return $response;
    }

    private function getSeasonTeamFromLeaderboard(array $leaderboard): array
    {
        return array_values(array_filter(
            $this->availableResources->getSeasonTeams(),
            fn ($seasonTeam) => $seasonTeam['id'] === $leaderboard['seasonTeamId'],
        ))[0];
    }
}
