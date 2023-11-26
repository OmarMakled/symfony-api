<?php

namespace App\Controller;

use App\DTO\PhotoDTO;
use App\Entity\Photo;
use App\Repository\PhotoRepository;
use App\Service\Validator\ValidatorService;
use App\EventListener\Event\PhotoUploadEvent;
use App\Resource\UserResource;
use App\Service\User\UserUpdatePhotoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    /**
     * @Route("/api/photos/{id}", methods="DELETE")
     */
    public function delete(Photo $photo, PhotoRepository $photoRepository, TranslatorInterface $translator): JsonResponse
    {
        if ($photo->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException($translator->trans('You do not have permission to delete this photo'));
        }
        $photoRepository->delete($photo);
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/photos", methods="POST")
     */
    public function add(Request $request, ValidatorService $validator, UserUpdatePhotoService $userUpdatePhotoService): JsonResponse
    {
        $photoDTO = PhotoDTO::createFromRequest($request);
        if (!$validator->isValid($photoDTO)) {
            return new JsonResponse(['error' => $validator->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $userUpdatePhotoService->update($this->getUser(), $photoDTO);

        return new JsonResponse(UserResource::toArray($this->getUser()), Response::HTTP_CREATED);
    }
}
