<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Photo;
use App\Factory\UserFactory;
use App\Tests\RollBackTrait;
use App\Factory\PhotoFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhotoRepositoryTest extends WebTestCase
{
    use RollBackTrait;

    public function testAdd(): void
    {
        $em = self::$container->get('doctrine')->getManager();
        $photoRepository = $em->getRepository(Photo::class);

        $photo = PhotoFactory::create(['user' => UserFactory::create()]);
        $photoRepository->add($photo);

        $this->assertCount(1, $photoRepository->findAll());
    }
}
