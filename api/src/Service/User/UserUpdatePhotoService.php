<?php

namespace App\Service\User;

use App\Entity\User;
use App\DTO\PhotoDTO;
use App\Repository\UserRepository;
use App\EventListener\Event\PhotoUploadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserUpdatePhotoService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function update(User $user, PhotoDTO $photoDTO): void
    {
        $this->eventDispatcher->dispatch(new PhotoUploadEvent(
            $photoDTO->photos,
            $user
        ), PhotoUploadEvent::class);

        $this->userRepository->add($user);
    }
}
