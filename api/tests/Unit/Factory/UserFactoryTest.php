<?php

namespace App\Tests\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $user = UserFactory::create();

        self::assertInstanceOf(User::class, $user);
        self::assertSame('John', $user->getFirstName());
        self::assertSame('Doe', $user->getLastName());
        self::assertSame('john.doe@example.com', $user->getEmail());
        self::assertSame('/', $user->getAvatar());
        self::assertSame('password', $user->getPassword());
        self::assertTrue($user->getIsActive());
    }
}
