<?php

namespace App\Resource;

use App\Entity\Photo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PhotoResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="url", type="string"),
 * )
 */
class PhotoResource
{
    public static function toArrayCollection(array $photos): array
    {
        $result = [];
        foreach ($photos as $photo) {
            $result['photos'][] = self::toArray($photo)['photo'];
        }
        return $result;
    }

    public static function toArray(Photo $photo): array
    {
        return [
            'photo' => [
                'id' => $photo->getId(),
                'name' => $photo->getName(),
                'url' => $photo->getUrl(),
            ]
        ];
    }
}
