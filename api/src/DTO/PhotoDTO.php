<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class PhotoDTO
{
    /**
     * @Assert\NotBlank(message="photo.blank")
     * @Assert\Type(type={"array"}, message="photo.type")
     * @Assert\Count(
     *      min=4,
     *      minMessage="photo.minCountMessage"
     * )
     * @Assert\All({
     *     @Assert\Image(
     *         mimeTypes={"image/jpeg", "image/png"},
     *         maxSize="5M",
     *         mimeTypesMessage="photo.mimeTypesMessage",
     *         maxSizeMessage="photo.maxSizeMessage"
     *     )
     * })
     */
    public readonly array $photos;

    public function __construct(
        array $photos,
    ) {
        $this->photos = $photos;
    }

    public static function createFromRequest(Request $request): self
    {
        return new static(
            $request->files->get('photos', [])
        );
    }
}
