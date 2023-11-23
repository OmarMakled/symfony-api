<?php

namespace App\Resource;

use OpenApi\Annotations as OA;
use App\Entity\User;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="full_name", type="string"),
 *     @OA\Property(property="avatar", type="string"),
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="created_at", type="string"),
 *     @OA\Property(property="updated_at", type="string"),
 *     @OA\Property(
 *         property="photos",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="url", type="string"),
 *         )
 *     ),
 * )
 */
class UserResource
{
    public static function toArrayCollection(array $users): array
    {
        $result = ['users' => []];
        foreach ($users as $user) {
            $result['users'][] = self::toArray($user)['user'];
        }
        return $result;
    }

    public static function toArray(User $user): array
    {
        return [
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'full_name' => $user->getFullName(),
                'avatar' => $user->getAvatar(),
                'is_active' => $user->getIsActive(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s'),
                'photos' => self::getPhotos($user)
            ]
        ];
    }

    public static function getPhotos(User $user): array
    {
        $photos = [];
        foreach ($user->getPhotos() as $photo) {
            $photos[] = PhotoResource::toArray($photo)['photo'];
        }
        return $photos;
    }
}
