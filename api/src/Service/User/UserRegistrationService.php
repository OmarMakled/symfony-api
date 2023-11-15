<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\Photo;
use App\EventListener\ExceptionListener;
use App\Repository\UserRepository;
use App\Service\Uploader\PhotoUploader;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordEncoderInterface $passwordEncoder, private readonly PhotoUploader $photoUploader)
    {
    }

    /**
     * @param UserDTO $userDTO
     * @return User
     * @throws HttpException
     * @see ExceptionListener
     */
    public function create(UserDTO $userDTO): User
    {
        $user = new User();
        $user->setFirstName($userDTO->firstName);
        $user->setLastName($userDTO->lastName);
        $user->setEmail($userDTO->email);
        $user->setPlainPassword($userDTO->password);
        $user->setAvatar($userDTO->avatar);
        if ($userDTO->photos) {
            foreach ($this->photoUploader->upload($userDTO->photos) as $file) {
                $photo = new Photo();
                $photo->setUrl($file->url);
                $photo->setName($file->name);
                $user->addPhoto($photo);
            }
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $this->userRepository->add($user);

        return $user;
    }
}
