<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\Photo;
use App\Repository\UserRepository;
use App\Resource\UserResource;
use App\Service\Uploader\PhotoUploader;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users/register", name="user_register", methods="POST")
     *
     * @OA\Post(
     *     path="/api/users/register",
     *     summary="Register a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="photos",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     )
     *                 ),
     *                 required={"first_name", "last_name", "email", "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(type="object", @OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user resource",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", ref=@Model(type=UserResource::class))
     *         )
     *     ),
     *     security={}
     * )
     */
    public function register(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, PhotoUploader $photoUploader): JsonResponse
    {
        $userDTO = UserDTO::createFromRequest($request);

        $errors = $validator->validate($userDTO);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $user = $userDTO->makeUser();
        foreach ($photoUploader->upload($userDTO->getPhotos()) as $file) {
            $photo = new Photo();
            $photo->setUrl($file->url);
            $photo->setName($file->name);
            $user->addPhoto($photo);
        }

        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $userRepository->add($user);

        return new JsonResponse(UserResource::toArray($user), 201);
    }

    /**
     * @Route("/api/users/login", name="user_login", methods="POST")
     *
     * @OA\Post(
     *     path="/api/users/login",
     *     summary="Login to the application",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", example="password123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns a JWT token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     )
     * )
     */
    public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user || !$encoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        return new JsonResponse(['token' => $jwtManager->create($user)]);
    }

    /**
     * @Route("/api/users/me", name="api_user_me", methods={"GET"})
     *
     * @OA\Get(
     *     path="/api/users/me",
     *     summary="Get information about the currently authenticated user",
     *     tags={"Users"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Returns the user resource",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", ref=@Model(type=UserResource::class))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse(UserResource::toArray($user));
    }
}
