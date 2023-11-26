<?php

namespace App\Controller;

use App\DTO\UserRegisterDTO;
use App\DTO\UserUpdateDTO;
use App\EventListener\ExceptionListener;
use App\EventListener\ResponseListener;
use App\Resource\UserResource;
use OpenApi\Annotations as OA;
use App\Service\User\UserLoginService;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Service\Validator\ValidatorService;
use App\Service\User\UserRegisterService;
use App\Service\User\UserUpdateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="array", @OA\Items(type="string")))
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
    public function register(Request $request, ValidatorService $validator, UserRegisterService $userRegistrationService): JsonResponse
    {
        $userDTO = UserRegisterDTO::createFromRequest($request);
        if (!$validator->isValid($userDTO)) {
            return new JsonResponse(['error' => $validator->getErrors()], Response::HTTP_BAD_REQUEST);
        }
        $user = $userRegistrationService->create($userDTO);
        return new JsonResponse(UserResource::toArray($user), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/users/login", name="user_login", methods="POST")
     * @see ExceptionListener
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
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function login(Request $request, UserLoginService $userLoginService): JsonResponse
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        if (empty($email) || empty($password)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid credentials');
        }
        $token = $userLoginService->login($request->request->get('email'), $request->request->get('password'));
        return new JsonResponse(['token' => $token]);
    }

    /**
     * @Route("/api/users/me", name="api_user_me", methods={"GET"})
     * @see ResponseListener
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
     *         @OA\JsonContent(type="object", @OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse(UserResource::toArray($user));
    }

    /**
     * @Route("/api/users", methods={"PUT"})
     * @return JsonResponse
     */
    public function update(Request $request, ValidatorService $validator, UserUpdateService $userService): JsonResponse
    {
        $userDTO = UserUpdateDTO::createFromRequest($request);
        if (!$validator->isValid($userDTO)) {
            return new JsonResponse(['error' => $validator->getErrors()], Response::HTTP_BAD_REQUEST);
        }
        $user = $userService->update($this->getUser(), $userDTO);
        return new JsonResponse(UserResource::toArray($user), Response::HTTP_OK);
    }
}
