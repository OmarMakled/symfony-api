<?php

namespace App\Controller\Admin;

use App\Resource\PhotoResource;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    /**
     * @Route("/api/admin/photos", methods="GET")
     *
     * @param PhotoRepository $photoRepository
     * @return JsonResponse
     */
    public function index(PhotoRepository $photoRepository): JsonResponse
    {
        $photos = $photoRepository->findAll([]);

        return new JsonResponse(PhotoResource::toArrayCollection($photos), Response::HTTP_OK);
    }
}
