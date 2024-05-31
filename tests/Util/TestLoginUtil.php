<?php

namespace Tests\Util;

use GuzzleHttp\Client;

readonly class TestLoginUtil
{
    public const DEFAULT_PASSWORD = 'password123!';

    public const DEFAULT_ADMIN_LIKE_PASSWORD = 'admin123!';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function loginWithEmailAndPassword(string $email, string $password): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => $email,
                'password' => $password
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function loginAsAdmin(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'admin@admin.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function loginAsModerator(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'moderator@moderator.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function loginAsEditor(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'editor@editor.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }
}
