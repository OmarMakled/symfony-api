<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\DTO\PhotoDTO;
use App\DTO\UserUpdateDTO;
use App\Resource\UserResource;
use App\Repository\UserRepository;
use App\Resource\PaginatorResource;
use App\Service\User\UserUpdateService;
use App\Service\Validator\ValidatorService;
use App\EventListener\Event\PhotoUploadEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public const LIMIT = 10;

    /**
     * @Route("/api/admin/users", methods="GET")
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function index(Request $request, UserRepository $userRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        /** @var Paginator */
        $pagination = $userRepository->paginate($page, self::LIMIT);
        $users = $pagination->getIterator()->getArrayCopy();

        $userResourceArray = UserResource::toArrayCollection($users);
        $paginatorResourceArray = PaginatorResource::toArray($pagination->count(), $page, self::LIMIT);
        $response = array_merge($paginatorResourceArray, $userResourceArray);

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api/admin/users/{id}", methods="GET")
     * @return JsonResponse
     */
    public function showUser(User $user): JsonResponse
    {
        return new JsonResponse(UserResource::toArray($user), Response::HTTP_OK);
    }

    /**
     * @Route("/api/admin/users/{id}", methods="DELETE")
     * @return JsonResponse
     */
    public function deleteUser(User $user, UserRepository $userRepository): JsonResponse
    {
        $userRepository->delete($user);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/admin/users/{id}/photos", methods="POST")
     *
     * @param PhotoRepository $photoRepository
     * @return JsonResponse
     */
    public function uploadPhotos(Request $request, User $user, ValidatorService $validator, EventDispatcherInterface $eventDispatcher, UserRepository $userRepository): JsonResponse
    {
        $photoDTO = PhotoDTO::createFromRequest($request);

        if (!$validator->isValid($photoDTO)) {
            return new JsonResponse(['error' => $validator->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $eventDispatcher->dispatch(new PhotoUploadEvent(
            $photoDTO->photos,
            $user
        ), PhotoUploadEvent::class);
        $userRepository->add($user);

        return new JsonResponse(UserResource::toArray($user), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/admin/users/{id}", methods={"PUT"})
     * @return JsonResponse
     */
    public function updateUser(Request $request, User $user, ValidatorService $validator, UserUpdateService $userService): JsonResponse
    {
        $userDTO = UserUpdateDTO::createFromRequest($request);
        if (!$validator->isValid($userDTO)) {
            return new JsonResponse(['error' => $validator->getErrors()], Response::HTTP_BAD_REQUEST);
        }
        $user = $userService->update($user, $userDTO);
        return new JsonResponse(UserResource::toArray($user), Response::HTTP_OK);
    }
}
