<?php

namespace App\Tests\Factory;

use App\Entity\User;
use App\Entity\Photo;
use App\Factory\UserFactory;
use App\Factory\PhotoFactory;
use PHPUnit\Framework\TestCase;

class PhotoFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $user = UserFactory::create(['first_name' => 'foo']);
        $photo = PhotoFactory::create(['user' =>  $user]);

        self::assertInstanceOf(Photo::class, $photo);
        self::assertSame($user, $photo->getUser());
        self::assertSame('/', $photo->getUrl());
        self::assertSame('name', $photo->getName());
    }
}
