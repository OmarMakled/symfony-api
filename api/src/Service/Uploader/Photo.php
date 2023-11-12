<?php

namespace App\Service\Uploader;

final class Photo
{
    public function __construct(public readonly string $url, public readonly string $name)
    {
    }
}
