<?php

namespace App\Service\User;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserLoginService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly UserPasswordEncoderInterface $encoder, private readonly JWTTokenManagerInterface $jwtManager)
    {
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     * @throws HttpException
     */
    public function login(string $email, string $password): string
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->encoder->isPasswordValid($user, $password)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }

        return $this->jwtManager->create($user);
    }
}
