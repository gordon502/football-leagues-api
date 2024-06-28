<?php

namespace Tests\Modules\Season;

use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class SeasonControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'seasons';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$seasons = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season $i",
                    'period' => "202$i",
                    'active' => $i % 2 === 0,
                    'leagueId' => $this->randomLeagueId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasons[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season $i",
                    'period' => "202$i",
                    'active' => $i % 2 === 0,
                    'leagueId' => $this->randomLeagueId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasons[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 10; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Season $i",
                    'period' => "202$i",
                    'active' => $i % 2 === 0,
                    'leagueId' => $this->randomLeagueId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$seasons[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season 1",
                'period' => "2024",
                'active' => true,
                'leagueId' => $this->randomLeagueId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'name' => "Season 1",
                'period' => "2024",
                'active' => true,
                'leagueId' => $this->randomLeagueId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$seasons as $resource) {
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

        $this->assertCount(count(TestAvailableResources::$seasons), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'period',
            'active',
            'leagueId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$seasons[0]['id'];

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
            'period',
            'active',
            'leagueId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$seasons[0]['id'];

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
            'period',
            'active',
            'leagueId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$seasons[0]['id'];

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
            'period',
            'active',
            'leagueId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$seasons[0]['id'];

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
            'period',
            'active',
            'leagueId'
        ];

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Updated",
                'period' => "2023",
                'active' => false,
                'leagueId' => $this->randomLeagueId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasons[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Updated",
                'period' => "2023",
                'active' => false,
                'leagueId' => $this->randomLeagueId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasons[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Updated",
                'period' => "2023",
                'active' => false,
                'leagueId' => $this->randomLeagueId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$seasons[0] = json_decode($response->getBody()->getContents(), true);
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

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Season Updated",
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
        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'name' => "Season Updated",
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $season = array_pop(TestAvailableResources::$seasons);
        $id = $season['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $season = array_pop(TestAvailableResources::$seasons);
        $id = $season['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $season = array_pop(TestAvailableResources::$seasons);
        $id = $season['id'];

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

        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$seasons[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfLeaguesDeletesSeasonsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $season = array_pop(TestAvailableResources::$seasons);
        $relatedSeasons = [
            $season,
            ...array_filter(
                TestAvailableResources::$seasons,
                fn($s) => $s['leagueId'] === $season['leagueId']
            )
        ];

        foreach ($relatedSeasons as $relatedSeason) {
            $response = $this->client->get("{$this->endpoint}/{$relatedSeason['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "leagues/{$season['leagueId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$leagues = array_values(array_filter(
            TestAvailableResources::$leagues,
            fn($l) => $l['id'] !== $season['leagueId']
        ));

        foreach ($relatedSeasons as $relatedTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$seasons = array_values(array_filter(
            TestAvailableResources::$seasons,
            fn($s) => $s['leagueId'] !== $season['leagueId']
        ));
    }

    private function randomLeagueId(): string
    {
        return TestAvailableResources::$leagues[
            array_rand(TestAvailableResources::$leagues)
        ]['id'];
    }
}
