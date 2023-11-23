<?php

namespace App\Controller\Admin;

use App\Resource\UserResource;
use App\Repository\UserRepository;
use App\Resource\PaginatorResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
