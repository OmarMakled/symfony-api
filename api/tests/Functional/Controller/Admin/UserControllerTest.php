<?php

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\User;
use App\Tests\BaseController;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseController
{
    public function testShowOnSuccess()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $token = $this->generateAdminToken();

        $this->client->request('GET', '/api/admin/users/' . $user->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();
        $user = json_decode($response->getContent(), true);
        self::assertUser($user);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteOnSuccess()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $token = $this->generateAdminToken();

        $this->client->request('DELETE', '/api/admin/users/' . $user->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testShowOnForbidden()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $token = $this->generateToken('user-2@mail.com', '1password');

        $this->client->request('GET', '/api/admin/users/' . $user->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();

        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testDeleteOnForbidden()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $token = $this->generateToken('user-2@mail.com', '1password');

        $this->client->request('DELETE', '/api/admin/users/' . $user->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();
        self::assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
