<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    public const PASSWORD = '1password';

    public static function create(array $data = [], UserPasswordEncoderInterface $passwordEncoder = null): User
    {
        $user = new User();
        $user->setFirstName($data['firstName'] ?? 'John');
        $user->setLastName($data['lastName'] ?? 'Doe');
        $user->setEmail($data['email'] ?? 'john.doe@example.com');
        $user->setAvatar($data['avatar'] ?? '/');
        $user->setPassword($data['password'] ?? self::PASSWORD);
        $user->setPlainPassword($data['password'] ?? self::PASSWORD);
        $user->setIsActive($data['isActive'] ?? true);

        if ($passwordEncoder) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }

        return $user;
    }
}
