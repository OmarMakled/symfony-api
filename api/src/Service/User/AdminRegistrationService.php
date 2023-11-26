<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;

class AdminRegistrationService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }


    public function create(User $user)
    {
        $user->setRoles([
            User::ROLE_ADMIN
        ]);
        $this->userRepository->add($user);
    }
}
