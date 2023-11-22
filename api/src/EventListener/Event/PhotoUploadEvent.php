<?php

namespace App\EventListener\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class PhotoUploadEvent extends Event
{
    public function __construct(public readonly array $photos, public readonly User $user)
    {
    }
}
