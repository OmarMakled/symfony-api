<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    public static function create(array $data = [], UserPasswordEncoderInterface $passwordEncoder = null): User
    {
        $user = new User();
        $user->setFirstName($data['firstName'] ?? 'John');
        $user->setLastName($data['lastName'] ?? 'Doe');
        $user->setEmail($data['email'] ?? 'john.doe@example.com');
        $user->setFullName($user->getFirstName() . ' ' . $user->getLastName());
        $user->setAvatar($data['avatar'] ?? '/');
        $user->setPassword($data['password'] ?? 'password');
        $user->setPlainPassword($data['password'] ?? 'password');
        $user->setIsActive($data['isActive'] ?? true);

        if ($passwordEncoder) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }

        return $user;
    }
}
