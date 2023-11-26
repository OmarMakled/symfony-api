<?php

namespace App\Service\User;

use App\DTO\UserUpdateDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\EventListener\ExceptionListener;
use App\EventListener\Event\PhotoUploadEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserUpdateService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordEncoderInterface $passwordEncoder, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @param User $user
     * @param UserUpdateDTO $userDTO
     * @return User
     * @throws HttpException
     * @see ExceptionListener
     */
    public function update(User $user, UserUpdateDTO $userDTO): User
    {
        $user->setFirstName($userDTO->firstName);
        $user->setLastName($userDTO->lastName);
        $user->setEmail($userDTO->email);
        $user->setPlainPassword($userDTO->password);
        $user->setAvatar($userDTO->avatar);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $this->userRepository->add($user);

        return $user;
    }
}
