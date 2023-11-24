<?php

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use DateTimeImmutable;
use App\Factory\UserFactory;
use App\Tests\BaseController;

class UserRepositoryTest extends BaseController
{
    public function testFindActiveUsersSinceDate(): void
    {
        $userRepository = $this->em->getRepository(User::class);

        $user = UserFactory::create(['email' => 'foo@mail.com', 'isActive' => false]);
        $userRepository->add($user);

        $result = $userRepository->findActiveUsersSinceDate(new DateTimeImmutable());

        $this->assertCount(6, $result);
    }
}
