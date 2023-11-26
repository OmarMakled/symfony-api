<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Factory\UserFactory;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class BaseController extends WebTestCase
{
    protected $em;
    protected $client;

    public function setUp(): void
    {
        $this->client = self::createClient(['environment' => 'test']);
        $this->client->disableReboot();

        $this->em = self::$container->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();

        $this->loadFixtures();
    }

    public function tearDown(): void
    {
        $this->em->rollback();
    }

    private function loadFixtures()
    {
        $userPasswordEncoderInterface = self::$container->get(UserPasswordEncoderInterface::class);
        $loader = new Loader();
        $loader->addFixture(new UserFixtures($userPasswordEncoderInterface));

        $executor = new ORMExecutor($this->em, new ORMPurger());
        $executor->execute($loader->getFixtures());
    }

    public function generateToken(string $email, string $password): string
    {
        $this->client->request('POST', '/api/users/login', ['email' => $email, 'password' => $password]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', $data);

        return $data['token'];
    }

    public function generateAdminToken()
    {
        $this->client->request('POST', '/api/users/login', ['email' => UserFactory::ADMIN, 'password' => UserFactory::PASSWORD]);
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertArrayHasKey('token', $data);

        return $data['token'];
    }

    public static function assertUser(array $data)
    {
        self::assertIsArray($data['user']);
        self::assertArrayHasKey('id', $data['user']);
        self::assertArrayHasKey('first_name', $data['user']);
        self::assertArrayHasKey('last_name', $data['user']);
        self::assertArrayHasKey('email', $data['user']);
        self::assertArrayHasKey('avatar', $data['user']);
        self::assertArrayHasKey('is_active', $data['user']);
        self::assertArrayHasKey('photos', $data['user']);
        self::assertArrayHasKey('isAdmin', $data['user']);
    }
}
