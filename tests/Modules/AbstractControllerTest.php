<?php

namespace Tests\Modules;

use GuzzleHttp\Client;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Tests\Util\TestLoginUtil;

// TODO: Collection tests!
abstract class AbstractControllerTest extends Assert
{
    protected readonly Client $client;

    protected readonly TestLoginUtil $loginUtil;

    protected string $endpoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->loginUtil = new TestLoginUtil($client);
    }

    public function runTests(): void
    {
        $reflection = new \ReflectionClass(static::class);

        echo $reflection->getName() . PHP_EOL;

        $this->testShouldReturnInitialCollection();
        echo "\t✅  testShouldReturnInitialCollection" . PHP_EOL;

        foreach ($reflection->getMethods() as $method) {
            if ($method->name === 'testShouldReturnInitialCollection') {
                continue;
            }

            if (str_starts_with($method->name, 'testShould')) {
                $method->invoke($this);

                echo "\t✅  {$method->name}" . PHP_EOL;
            }
        }
    }

    abstract public function clearAfterTests(): void;

    protected function testShouldReturnInitialCollection(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertEmpty($json['data']);
        $this->assertEquals(0, $json['pagination']['total']);
        $this->assertEquals(1, $json['pagination']['currentPage']);
    }

    abstract protected function testShouldCheckIfAdminCanCreateResource(): void;

    abstract protected function testShouldCheckIfModeratorCanCreateResource(): void;

    abstract protected function testShouldCheckIfEditorCanCreateResource(): void;

    abstract protected function testShouldCheckIfUserCanCreateResource(): void;

    abstract protected function testShouldCheckIfGuestCanCreateResource(): void;

    abstract protected function testShouldReturnPreviouslyCreatedResources(): void;

    abstract protected function testShouldReturnCollectionOfAllCreatedResources(): void;

    abstract protected function testShouldCheckIfAdminCanReadResource(): void;

    abstract protected function testShouldCheckIfModeratorCanReadResource(): void;

    abstract protected function testShouldCheckIfEditorCanReadResource(): void;

    abstract protected function testShouldCheckIfUserCanReadResource(): void;

    abstract protected function testShouldCheckIfGuestCanReadResource(): void;

    abstract protected function testShouldCheckEditableFieldsByAdmin(): void;

    abstract protected function testShouldCheckNotEditableFieldsByAdmin(): void;

    abstract protected function testShouldCheckEditableFieldsByModerator(): void;

    abstract protected function testShouldCheckNotEditableFieldsByModerator(): void;

    abstract protected function testShouldCheckEditableFieldsByEditor(): void;

    abstract protected function testShouldCheckNotEditableFieldsByEditor(): void;

    abstract protected function testShouldCheckEditableFieldsByUser(): void;

    abstract protected function testShouldCheckNotEditableFieldsByUser(): void;

    abstract protected function testShouldCheckEditableFieldsByGuest(): void;

    abstract protected function testShouldCheckNotEditableFieldsByGuest(): void;

    abstract protected function testShouldCheckIfAdminCanDeleteResource(): void;

    abstract protected function testShouldCheckIfModeratorCanDeleteResource(): void;

    abstract protected function testShouldCheckIfEditorCanDeleteResource(): void;

    abstract protected function testShouldCheckIfUserCanDeleteResource(): void;

    abstract protected function testShouldCheckIfGuestCanDeleteResource(): void;

    protected function assertReadableFieldsFromResponse(array $readableFields, ResponseInterface $response): void
    {
        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        foreach ($readableFields as $field) {
            $this->assertArrayHasKey($field, $json);
        }
        foreach ($json as $field => $value) {
            $this->assertContains($field, $readableFields);
        }
    }
}
