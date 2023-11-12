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
 *     @OA\Property(property="avatar", type="string"),
 *     @OA\Property(property="is_active", type="boolean"),
 * )
 */
class UserResource
{
    public static function toArray(User $user): array
    {
        return [
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'avatar' => $user->getAvatar(),
                'is_active' => $user->getIsActive(),
            ]
        ];
    }
}
