<?php

namespace Tests\Modules\Team;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class TeamControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'teams';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$teams = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Organizational Unit $i",
                    'yearEstablished' => $i,
                    'colors' => "Address $i",
                    'country' => "City $i",
                    'address' => "Postal Code $i",
                    'city' => "Phone $i",
                    'postalCode' => "Postal Code $i",
                    'site' => "Site $i",
                    'stadium' => "Stadium $i",
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$teams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Organizational Unit $i",
                    'yearEstablished' => $i,
                    'colors' => "Address $i",
                    'country' => "City $i",
                    'address' => "Postal Code $i",
                    'city' => "Phone $i",
                    'postalCode' => "Postal Code $i",
                    'site' => "Site $i",
                    'stadium' => "Stadium $i",
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$teams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'name' => "Organizational Unit $i",
                    'yearEstablished' => $i,
                    'colors' => "Address $i",
                    'country' => "City $i",
                    'address' => "Postal Code $i",
                    'city' => "Phone $i",
                    'postalCode' => "Postal Code $i",
                    'site' => "Site $i",
                    'stadium' => "Stadium $i",
                    'organizationalUnitId' => $this->randomOrganizationalUnitId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$teams[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Organizational Unit 1",
                'yearEstablished' => 1900,
                'colors' => "Address 1",
                'country' => "City 1",
                'address' => "Postal Code 1",
                'city' => "Phone 1",
                'postalCode' => "Postal Code 1",
                'site' => "Site 1",
                'stadium' => "Stadium 1",
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'name' => "Organizational Unit 1",
                'yearEstablished' => 1900,
                'colors' => "Address 1",
                'country' => "City 1",
                'address' => "Postal Code 1",
                'city' => "Phone 1",
                'postalCode' => "Postal Code 1",
                'site' => "Site 1",
                'stadium' => "Stadium 1",
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$teams as $resource) {
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

        $this->assertCount(count(TestAvailableResources::$teams), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'name',
            'yearEstablished',
            'colors',
            'country',
            'address',
            'city',
            'postalCode',
            'site',
            'stadium',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$teams[0]['id'];

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
            'yearEstablished',
            'colors',
            'country',
            'address',
            'city',
            'postalCode',
            'site',
            'stadium',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$teams[0]['id'];

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
            'yearEstablished',
            'colors',
            'country',
            'address',
            'city',
            'postalCode',
            'site',
            'stadium',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$teams[0]['id'];

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
            'yearEstablished',
            'colors',
            'country',
            'address',
            'city',
            'postalCode',
            'site',
            'stadium',
            'organizationalUnitId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$teams[0]['id'];

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
            'yearEstablished',
            'colors',
            'country',
            'address',
            'city',
            'postalCode',
            'site',
            'stadium',
            'organizationalUnitId'
        ];

        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Organizational Unit 1 Updated",
                'yearEstablished' => 1900,
                'colors' => "Address 1 Updated",
                'country' => "City 1 Updated",
                'address' => "Postal Code 1 Updated",
                'city' => "Phone 1 Updated",
                'postalCode' => "Postal Code 1 Updated",
                'site' => "Site 1 Updated",
                'stadium' => "Stadium 1 Updated",
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$teams[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Organizational Unit 1 Updated",
                'yearEstablished' => 1900,
                'colors' => "Address 1 Updated",
                'country' => "City 1 Updated",
                'address' => "Postal Code 1 Updated",
                'city' => "Phone 1 Updated",
                'postalCode' => "Postal Code 1 Updated",
                'site' => "Site 1 Updated",
                'stadium' => "Stadium 1 Updated",
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$teams[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => "Organizational Unit 1 Updated",
                'yearEstablished' => 1900,
                'colors' => "Address 1 Updated",
                'country' => "City 1 Updated",
                'address' => "Postal Code 1 Updated",
                'city' => "Phone 1 Updated",
                'postalCode' => "Postal Code 1 Updated",
                'site' => "Site 1 Updated",
                'stadium' => "Stadium 1 Updated",
                'organizationalUnitId' => $this->randomOrganizationalUnitId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$teams[0] = json_decode($response->getBody()->getContents(), true);
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

        $response = $this->updateTeamRequest(
            0,
            $token,
            [
                'yearEstablished' => 1900,
            ]
        );

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByGuest(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByGuest(): void
    {
        $response = $this->updateTeamRequest(
            0,
            null,
            [
                'yearEstablished' => 1900,
            ]
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $team = array_pop(TestAvailableResources::$teams);
        $id = $team['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $team = array_pop(TestAvailableResources::$teams);
        $id = $team['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $team = array_pop(TestAvailableResources::$teams);
        $id = $team['id'];

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

        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$teams[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfOrganizationalUnitDeletesTeamAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $team = array_pop(TestAvailableResources::$teams);
        $relatedTeams = [
            $team,
            ...array_filter(
                TestAvailableResources::$teams,
                fn($t) => $t['organizationalUnitId'] === $team['organizationalUnitId']
            )
        ];

        foreach ($relatedTeams as $relatedTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedTeam['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "organizational-units/{$team['organizationalUnitId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$organizationalUnits = array_values(array_filter(
            TestAvailableResources::$organizationalUnits,
            fn($ou) => $ou['id'] !== $team['organizationalUnitId']
        ));

        foreach ($relatedTeams as $relatedTeam) {
            $response = $this->client->get("{$this->endpoint}/{$relatedTeam['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$teams = array_values(array_filter(
            TestAvailableResources::$teams,
            fn($t) => $t['organizationalUnitId'] !== $team['organizationalUnitId']
        ));
    }

    private function updateTeamRequest(
        int $teamIndex,
        ?string $token,
        array $data
    ): ResponseInterface {
        $headers = [];
        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        $id = TestAvailableResources::$teams[$teamIndex]['id'];

        return $this->client->put(
            "{$this->endpoint}/{$id}",
            ['headers' => $headers, 'json' => $data]
        );
    }

    private function randomOrganizationalUnitId(): string
    {
        return TestAvailableResources::$organizationalUnits[
            array_rand(TestAvailableResources::$organizationalUnits)
        ]['id'];
    }
}
