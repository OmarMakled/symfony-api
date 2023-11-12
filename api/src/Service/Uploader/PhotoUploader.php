<?php

namespace App\Service\Uploader;

use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PhotoUploader
{
    public function __construct(private readonly string $uploadDir, private readonly string $uploadUrl)
    {
    }

    public function upload($files): array
    {
        $uploadedPhotos = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                try {
                    $uploadedPhotos[] = $this->copy($file);
                } catch (Exception $e) {
                    throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
                }
            }
        }

        return $uploadedPhotos;
    }

    private function copy(UploadedFile $file): Photo
    {
        $filesystem = new Filesystem();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $targetPath = $this->uploadDir . $fileName;
        $filesystem->copy($file->getPathname(), $targetPath, true);

        return new Photo(
            url: $this->uploadUrl . $fileName,
            name: $file->getClientOriginalName()
        );
    }
}
