<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Factory\PhotoFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordEncoderInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = UserFactory::create(['email' => "user-$i@mail.com"], $this->passwordEncoder);
            $this->loadPhotos($manager, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadPhotos(ObjectManager $manager, User $user)
    {
        for ($i = 1; $i <= 5; $i++) {
            $photo = PhotoFactory::create(['user' => $user]);
            $manager->persist($photo);
        }
    }
}
