<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\RollBackTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserControllerTest extends WebTestCase
{
    use RollBackTrait;

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

    public function testRegisterOnFail()
    {
        $this->createUser('foo@mail.com', 'password');
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'foo@mail.com',
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
        $this->createUser('foo@mail.com', 'password');
        $this->generateToken(['email' => 'foo@mail.com', 'password' => 'password']);

        $this->client->request('POST', '/api/users/login', ['email' => 'foo@mail.com', 'password' => 'xpassword']);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertEquals(401, $response->getStatusCode());
        self::assertArrayHasKey('error', $data);
    }

    public function testMeOnSuccess(): void
    {
        $this->createUser('foo@mail.com', 'password');
        $token = $this->generateToken(['email' => 'foo@mail.com', 'password' => 'password']);
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

    private function createUser(string $email, string $password): User
    {
        $userPasswordEncoderInterface = self::$container->get(UserPasswordEncoderInterface::class);
        $userRepository = $this->em->getRepository(User::class);
        $user = UserFactory::create(['email' => $email, 'password' => $password], $userPasswordEncoderInterface);
        $userRepository->add($user);

        return $user;
    }

    private function generateToken(array $credentials): string
    {
        $this->client->request('POST', '/api/users/login', $credentials);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', $data);

        return $data['token'];
    }

    private static function assertUser(array $data)
    {
        self::assertIsArray($data['user']);
        self::assertArrayHasKey('id', $data['user']);
        self::assertArrayHasKey('first_name', $data['user']);
        self::assertArrayHasKey('last_name', $data['user']);
        self::assertArrayHasKey('email', $data['user']);
        self::assertArrayHasKey('avatar', $data['user']);
        self::assertArrayHasKey('is_active', $data['user']);
        self::assertArrayHasKey('photos', $data['user']);
    }
}
