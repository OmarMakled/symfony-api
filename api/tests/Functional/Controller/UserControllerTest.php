<?php

namespace App\Tests\Functional\Controller;

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Tests\BaseController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserControllerTest extends BaseController
{
    public function testRegisterOnSuccess()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123'
        ];
        $this->client->request('POST', '/api/users/register', $userData);
        $response = $this->client->getResponse();

        self::assertUser(json_decode($response->getContent(), true));
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testRegisterOnFailEmailAlreadyExists()
    {
        $userRepo = self::$container->get(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->getEmail(),
            'password' => 'password123'
        ];

        $this->client->request('POST', '/api/users/register', $userData);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertArrayHasKey('error', $data);
        self::assertArrayHasKey('email', $data['error']);
    }

    public function testRegisterWithPhotos(): void
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'avatar' => 'http://foo.com/default-avatar.jpg',
        ];
        $file = new UploadedFile(
            __DIR__ . '/test.jpg',
            'test.jpg',
            'image/jpeg',
            null,
            true
        );
        $photoData = [
            'photos' => [$file, $file, $file, $file,],
        ];
        $this->client->request('POST', '/api/users/register', $userData, $photoData);
        $response = $this->client->getResponse();

        self::assertUser(json_decode($response->getContent(), true));
        self::assertEquals(201, $response->getStatusCode());
    }

    public function testLogin(): void
    {
        $this->client->request('POST', '/api/users/login', ['email' => 'user-1@mail.com', 'password' => 'wrongpassword']);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertEquals(401, $response->getStatusCode());
        self::assertArrayHasKey('error', $data);
    }

    public function testMeOnSuccess(): void
    {
        $userRepo = self::$container->get(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $token = $this->generateToken($user->getEmail(), UserFactory::PASSWORD);
        $this->client->request('GET', '/api/users/me', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $response = $this->client->getResponse();

        self::assertUser(json_decode($response->getContent(), true));
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testMeOnFail(): void
    {
        $this->client->request('GET', '/api/users/me', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer x'
        ]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertEquals(401, $response->getStatusCode());
        self::assertArrayHasKey('error', $data);
    }
}
