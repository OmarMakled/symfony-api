<?php

namespace App\DTO;

use App\Service\Validator\Constraints as CustomAssert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDTO
{
    public const DEFAULT_AVATAR = "http://localhost:8080/uploads/avatar.jpg";

    /**
     * @Assert\NotBlank(message="firstName.blank")
     * @Assert\Length(
     *      min=2,
     *      max=25,
     *      minMessage="firstName.minLength",
     *      maxMessage="firstName.maxLength"
     * )
     */
    public readonly ?string $firstName;

    /**
     * @Assert\NotBlank(message="lastName.blank")
     * @Assert\Length(
     *      min=2,
     *      max=25,
     *      minMessage="lastName.minLength",
     *      maxMessage="lastName.maxLength"
     * )
     */
    public readonly ?string $lastName;

    /**
     * @Assert\NotBlank(message="email.blank")
     * @Assert\Email(message="email.valid")
     * @CustomAssert\UniqueEmail
     */
    public readonly ?string $email;

    /**
     * @Assert\NotBlank(message="password.blank")
     * @CustomAssert\StrongPassword(message="password.strong")
     */
    public readonly ?string $password;

    /**
     * @Assert\Url(message="avatar.url")
     */
    public readonly string $avatar;

    /**
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
    public readonly ?array $photos;

    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?array $photos,
        string $avatar
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->photos = $photos;
        $this->avatar = $avatar;
    }

    public static function createFromRequest(Request $request): self
    {
        $avatar = $request->request->get('avatar');
        return new static(
            $request->request->get('first_name'),
            $request->request->get('last_name'),
            $request->request->get('email'),
            $request->request->get('password'),
            $request->files->get('photos'),
            ($avatar === '' || $avatar === null) ?  self::DEFAULT_AVATAR : $avatar
        );
    }

    public static function createFromArray(array $data): self
    {
        $avatar = $data['avatar'] ?? '';
        return new static(
            $data['firstName'] ?? '',
            $data['lastName'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['photos'] ?? null,
            ($avatar === '' || $avatar === null) ?  self::DEFAULT_AVATAR : $avatar
        );
    }
}
