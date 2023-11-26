<?php

namespace App\EventListener;

use App\Entity\Photo;
use App\Service\Uploader\PhotoUploader;
use App\EventListener\Event\PhotoUploadEvent;
use App\Repository\UserRepository;

class PhotoUploadListener
{
    public function __construct(private readonly PhotoUploader $photoUploader, private readonly UserRepository $userRepository)
    {
    }

    public function onPhotoUpload(PhotoUploadEvent $event): void
    {
        foreach ($this->photoUploader->upload($event->photos) as $file) {
            $photo = new Photo();
            $photo->setUrl($file->url);
            $photo->setName($file->name);
            $event->user->addPhoto($photo);
        }

        $this->userRepository->add($event->user);
    }
}
