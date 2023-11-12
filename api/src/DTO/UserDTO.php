<?php

namespace App\DTO;

use App\Entity\User;
use App\Service\Validator\Constraints as CustomAssert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @Assert\NotBlank(message="firstName cannot be blank")
     * @Assert\Length(
     *      min=2,
     *      max=25,
     *      minMessage="firstName must be at least {{ limit }} characters long",
     *      maxMessage="firstName cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $firstName;

    /**
     * @Assert\NotBlank(message="lastName cannot be blank")
     * @Assert\Length(
     *      min=2,
     *      max=25,
     *      minMessage="lastName must be at least {{ limit }} characters long",
     *      maxMessage="lastName cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $lastName;

    /**
     * @Assert\NotBlank(message="email cannot be blank")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @CustomAssert\UniqueEmail
     */
    private ?string $email;

    /**
     * @Assert\NotBlank(message="password cannot be blank")
     * @CustomAssert\StrongPassword
     */
    private ?string $password;

    /**
     * @var ?string
     */
    private ?string $avatar;

    /**
     * @Assert\Type(
     *     type={"array", "null"},
     *     message="Photos must be an array or null."
     * )
     * @Assert\All({
     *     @Assert\Image(
     *         mimeTypes={"image/jpeg", "image/png"},
     *         mimeTypesMessage="Only JPEG and PNG images are allowed.",
     *         maxSize="5M",
     *         maxSizeMessage="The file is too large. Maximum allowed size is {{ limit }}."
     *     )
     * })
     */
    private ?array $photos;

    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $password,
        ?string $avatar,
        ?array $photos
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
        $this->photos = $photos;
    }

    public static function createFromRequest(Request $request): self
    {
        return new static(
            $request->request->get('first_name'),
            $request->request->get('last_name'),
            $request->request->get('email'),
            $request->request->get('password'),
            $request->request->get('avatar'),
            $request->files->get('photos', [])
        );
    }

    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    public function makeUser(): User
    {
        $user = new User();
        $user->setFirstName($this->firstName);
        $user->setLastName($this->lastName);
        $user->setEmail($this->email);
        $user->setPlainPassword($this->password);
        $user->setAvatar($this->avatar);
        $user->setFullName($this->lastName . ' ' . $this->firstName);
        $user->setIsActive(count($this->getPhotos()) >= 4);
        $user->setAvatar($this->avatar ?: '/custom/avatar');

        return $user;
    }
}
