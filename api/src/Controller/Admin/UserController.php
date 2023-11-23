<?php

namespace App\Controller\Admin;

use App\Resource\UserResource;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/api/admin/users", methods="GET")
     *
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll([]);

        return new JsonResponse(UserResource::toArrayCollection($users), Response::HTTP_OK);
    }
}
