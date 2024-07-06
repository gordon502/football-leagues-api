<?php

namespace Tests\Modules\League;

use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class LeagueControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'leagues';

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
                    'name' => "League $i",
                    'active' => $i % 2 === 0,
                    'level' => $i,
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
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
                    'name' => "League $i",
                    'active' => $i % 2 === 0,
                    'level' => $i,
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
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
                    'name' => "League $i",
                    'active' => $i % 2 === 0,
                    'level' => $i,
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
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
                'name' => "League 1",
                'active' => true,
                'level' => 1,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'name' => "League 1",
                'active' => true,
                'level' => 1,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach ($this->availableResources->getLeagues() as $resource) {
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

        $this->assertCount(count($this->availableResources->getLeagues()), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'active',
            'level',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getLeagues()[0]['id'];

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
            'active',
            'level',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getLeagues()[0]['id'];

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
            'active',
            'level',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getLeagues()[0]['id'];

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
            'active',
            'level',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getLeagues()[0]['id'];

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
            'active',
            'level',
            'organizationalUnitId'
        ];

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "League Updated",
                'active' => false,
                'level' => 3,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
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

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "League Updated",
                'active' => false,
                'level' => 3,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
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

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "League Updated",
                'active' => false,
                'level' => 3,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
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

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "League Updated",
                'active' => false,
                'level' => 3,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
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
        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'name' => "League Updated",
                'active' => false,
                'level' => 3,
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $league = $this->availableResources->getLeagues()[0];
        $id = $league['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $league = $this->availableResources->getLeagues()[0];
        $id = $league['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $league = $this->availableResources->getLeagues()[0];
        $id = $league['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getLeagues()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfOrganizationalUnitDeletesLeagueAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $league = $this->availableResources->getLeagues()[0];
        $relatedLeagues = [
            $league,
            ...array_filter(
                array_slice($this->availableResources->getLeagues(), 1),
                fn($l) => $l['organizationalUnitId'] === $league['organizationalUnitId']
            )
        ];

        foreach ($relatedLeagues as $relatedLeague) {
            $response = $this->client->get("{$this->endpoint}/{$relatedLeague['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "organizational-units/{$league['organizationalUnitId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());

        foreach ($relatedLeagues as $relatedTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    private function randomOrganizationalUnitId(): string
    {
        $organizationalUnits = $this->availableResources->getOrganizationalUnits();

        return $organizationalUnits[
            array_rand($organizationalUnits)
        ]['id'];
    }
}
