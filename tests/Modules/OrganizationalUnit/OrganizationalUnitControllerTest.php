<?php

namespace Tests\Modules\OrganizationalUnit;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class OrganizationalUnitControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'organizational-units';

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
                    'name' => "Organizational Unit $i",
                    'country' => "Country $i",
                    'address' => "Address $i",
                    'city' => "City $i",
                    'postalCode' => "Postal Code $i",
                    'phone' => "Phone $i",
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => 'Organizational Unit 2',
                'country' => 'Country 2',
                'address' => 'Address 2',
                'city' => 'City 2',
                'postalCode' => 'Postal Code 2',
                'phone' => 'Phone 2',
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => 'Organizational Unit 3',
                'country' => 'Country 3',
                'address' => 'Address 3',
                'city' => 'City 3',
                'postalCode' => 'Postal Code 3',
                'phone' => 'Phone 3',
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => 'Organizational Unit 4',
                'country' => 'Country 4',
                'address' => 'Address 4',
                'city' => 'City 4',
                'postalCode' => 'Postal Code 4',
                'phone' => 'Phone 4',
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'name' => 'Organizational Unit 5',
                'country' => 'Country 5',
                'address' => 'Address 5',
                'city' => 'City 5',
                'postalCode' => 'Postal Code 5',
                'phone' => 'Phone 5',
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach ($this->availableResources->getOrganizationalUnits() as $resource) {
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

        $this->assertCount(count($this->availableResources->getOrganizationalUnits()), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = ['id', 'name', 'country', 'address', 'city', 'postalCode', 'phone'];

        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = ['id', 'name', 'country', 'address', 'city', 'postalCode', 'phone'];

        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = ['id', 'name', 'country', 'address', 'city', 'postalCode', 'phone'];

        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = ['id', 'name', 'country', 'address', 'city', 'postalCode', 'phone'];
        
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = ['id', 'name', 'country', 'address', 'city', 'postalCode', 'phone'];

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'name' => 'Organizational Unit 1 Updated',
                'country' => 'Country 1 Updated',
                'address' => 'Address 1 Updated',
                'city' => 'City 1 Updated',
                'postalCode' => 'Postal Code 1 Updated',
                'phone' => 'Phone 1 Updated',
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
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $response = $this->updateOrganizationalUnitRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            [
                'name' => 'Organizational Unit 1 Updated',
            ]
        );
        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByEditor(): void
    {
        $response = $this->updateOrganizationalUnitRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            [
                'name' => 'Organizational Unit 1 Updated',
            ]
        );
        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByUser(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->updateOrganizationalUnitRequest(
            0,
            $token,
            [
                'name' => 'Organizational Unit 1 Updated',
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
        $response = $this->updateOrganizationalUnitRequest(
            0,
            null,
            [
                'name' => 'Organizational Unit 1 Updated',
            ]
        );
        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $organizationalUnit = $this->availableResources->getOrganizationalUnits()[0];
        $id = $organizationalUnit['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getOrganizationalUnits()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    private function updateOrganizationalUnitRequest(
        int $organizationalUnitIndex,
        ?string $token,
        array $data
    ): ResponseInterface {
        $headers = [];
        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        $id = $this->availableResources->getOrganizationalUnits()[$organizationalUnitIndex]['id'];

        return $this->client->put(
            "{$this->endpoint}/{$id}",
            ['headers' => $headers, 'json' => $data]
        );
    }
}
