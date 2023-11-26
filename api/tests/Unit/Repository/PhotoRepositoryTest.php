<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Photo;
use App\Factory\UserFactory;
use App\Factory\PhotoFactory;
use App\Tests\BaseController;

class PhotoRepositoryTest extends BaseController
{
    public function testAdd(): void
    {
        $photoRepository = $this->em->getRepository(Photo::class);
        $count = $photoRepository->count([]);

        $photo = PhotoFactory::create(['user' => UserFactory::create()]);
        $photoRepository->add($photo);

        $this->assertEquals(++$count, $photoRepository->count([]));
    }
}
