<?php

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use DateTimeImmutable;
use App\Factory\UserFactory;
use App\Tests\RollBackTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    use RollBackTrait;

    public function testFindActiveUsersSinceDate(): void
    {
        $em = self::$container->get('doctrine')->getManager();
        $userRepository = $em->getRepository(User::class);

        $user = UserFactory::create(['email' => 'foo@mail.com', 'isActive' => false]);
        $userRepository->add($user);

        $result = $userRepository->findActiveUsersSinceDate(new DateTimeImmutable());

        $this->assertCount(0, $result);
    }
}
