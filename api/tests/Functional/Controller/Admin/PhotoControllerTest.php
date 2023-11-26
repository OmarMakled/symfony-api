<?php

namespace App\Tests\Functional\Controller\Admin;

use App\Entity\Photo;
use App\Tests\BaseController;
use Symfony\Component\HttpFoundation\Response;

class PhotoControllerTest extends BaseController
{
    public function testDeleteOnSuccess()
    {
        $userRepo = $this->em->getRepository(Photo::class);
        $photo = $userRepo->findOneBy([]);
        $token = $this->generateAdminToken();

        $this->client->request('DELETE', '/api/admin/photos/' . $photo->getId(), [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $response = $this->client->getResponse();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
