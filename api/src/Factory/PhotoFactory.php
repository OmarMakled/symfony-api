<?php

namespace App\Factory;

use App\Entity\User;
use App\Entity\Photo;

class PhotoFactory
{
    public static function create(array $data = []): Photo
    {
        $photo = new Photo();
        $photo->setUrl($data['url'] ?? '/');
        $photo->setName($data['url'] ?? 'name');
        $photo->setUser($data['user'] ?? new User());

        return $photo;
    }
}
