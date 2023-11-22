<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Factory\UserFactory;

class PhotoControllerTest extends BaseController
{
    public function testDeleteOnSuccess()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $photo = $user->getPhotos()->first();
        $token = $this->generateToken('user-1@mail.com', UserFactory::PASSWORD);

        $this->client->request('DELETE', '/api/photos/' . $photo->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();

        self::assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteOnForbidden()
    {
        $userRepo = $this->em->getRepository(User::class);
        $user = $userRepo->findOneBy(['email' => 'user-1@mail.com']);
        $photo = $user->getPhotos()->first();
        $token = $this->generateToken('user-2@mail.com', UserFactory::PASSWORD);

        $this->client->request('DELETE', '/api/photos/' . $photo->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();

        self::assertEquals(403, $response->getStatusCode());
    }

    public function testDeleteOnNotFound()
    {
        $token = $this->generateToken('user-1@mail.com', UserFactory::PASSWORD);

        $this->client->request('DELETE', '/api/photos/100', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $response = $this->client->getResponse();

        self::assertEquals(404, $response->getStatusCode());
    }
}
